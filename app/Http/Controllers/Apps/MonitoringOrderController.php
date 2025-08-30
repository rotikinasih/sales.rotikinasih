<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\MasterProduk;
use App\Models\MonitoringOrder;
use App\Models\OrderPenjualan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class MonitoringOrderController extends Controller
{
    public function index()
    {
        $search = request()->search;
        $tanggal = request()->tanggal;

        // Jika tidak ada filter tanggal, pakai hari ini WIB
        if (!$tanggal) {
            $tanggal = now()->setTimezone('Asia/Jakarta')->toDateString();
        }

        $userOutletIds = auth()->user()->outlets->pluck('id')->toArray();

        $monitoringOrders = MonitoringOrder::with('orderPenjualan.details.master_produk')
    ->when($search, function ($q) use ($search) {
        $q->where(function ($query) use ($search) {
            $query->whereHas('orderPenjualan', function ($sub) use ($search) {
                $sub->where('nama', 'like', '%' . $search . '%'); // Nama Pemesan
            })->orWhereHas('orderPenjualan.details.master_produk', function ($sub2) use ($search) {
                $sub2->where('nama_produk', 'like', '%' . $search . '%') // Nama Produk
                      ->orWhere('kode', 'like', '%' . $search . '%');   // Kode Produk
            });
        });
    })

            ->when($tanggal, function ($q) use ($tanggal) {
                $q->whereHas('orderPenjualan', function ($sub) use ($tanggal) {
                    $sub->whereDate('tanggal_pembuatan', $tanggal);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $orders = OrderPenjualan::with('details.master_produk')->get();

        $produkKodes = MasterProduk::select('kode', 'nama_produk')
            ->groupBy('kode', 'nama_produk')
            ->get();

        // Rekap total jumlah berdasarkan nama produk, hanya untuk pesanan hari ini WIB
        $today = now()->setTimezone('Asia/Jakarta')->toDateString();
        $totalJumlahPerProduk = [];
        foreach ($monitoringOrders as $monitoring) {
            // Pastikan hanya order dengan tanggal_pembuatan hari ini
            if ($monitoring->orderPenjualan->tanggal_pembuatan == $today) {
                foreach ($monitoring->orderPenjualan->details as $detail) {
                    $kode = $detail->master_produk->kode ?? '-';
                    $nama = $detail->master_produk->nama_produk ?? '-';
                    $key = $nama . ' (' . $kode . ')';
                    $totalJumlahPerProduk[$key] = ($totalJumlahPerProduk[$key] ?? 0) + $detail->jumlah_beli;
                }
            }
        }

        $masterKendaraan = \App\Models\MasterKendaraan::where('status', 2)->get();

        return Inertia::render('Apps/MonitoringOrder/Index', [
            'monitoringOrders' => $monitoringOrders,
            'orders' => $orders,
            'produkKodes' => $produkKodes,
            'totalJumlahPerProduk' => $totalJumlahPerProduk,
            'filters' => [
                'search' => $search,
                'tanggal' => $tanggal,
            ],
            'masterKendaraan' => $masterKendaraan,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_penjualan_id' => 'required|exists:order_penjualan,id',
            'status_produksi' => 'required|in:1,2',
        ]);

        $existing = MonitoringOrder::where('order_penjualan_id', $request->order_penjualan_id)->first();
        if ($existing) {
            return redirect()->route('apps.monitoringorder.index');
        }

        $order = OrderPenjualan::findOrFail($request->order_penjualan_id);

        MonitoringOrder::create([
            'order_penjualan_id' => $order->id,
            'status_produksi' => $request->status_produksi,
            'tanggal_pembuatan' => $order->tanggal_pembuatan,
            'jam_pengiriman' => $order->jam_pengiriman,
            'keterangan' => $request->keterangan,
            'keterangan_staf' => $request->keterangan_staf,
            'created_id' => Auth::id(),
        ]);

        return redirect()->route('apps.monitoringorder.index');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status_produksi' => 'required|in:1,2',
            'tanggal_pembuatan' => 'nullable|date',
            'jam_pengiriman' => 'nullable|in:Pagi,Siang,Sore,Malam',
        ]);

        $monitoringOrder = MonitoringOrder::findOrFail($id);
        $monitoringOrder->update([
            'status_produksi' => $request->status_produksi,
            'tanggal_pembuatan' => $request->tanggal_pembuatan,
            'jam_pengiriman' => $request->jam_pengiriman,
            'keterangan' => $request->keterangan,
            'keterangan_staf' => $request->keterangan_staf,
        ]);

        return redirect()->route('apps.monitoringorder.index');
    }

    public function orderProduksi(Request $request)
    {
        $kode = $request->kode;

        $orders = MonitoringOrder::with('orderPenjualan.details.master_produk')
            ->whereHas('orderPenjualan.details.master_produk', function ($q) use ($kode) {
                $q->where('kode', $kode);
            })
            ->get();

        return Inertia::render('Apps/OrderProduksi/Index', [
            'kode' => $kode,
            'orders' => $orders,
        ]);
    }

    public function export(Request $request)
    {
        $tanggal = $request->input('tanggal') ?: now()->setTimezone('Asia/Jakarta')->toDateString();
        $type = $request->input('type') ?? 'pdf';
        $kode = $request->input('kode');

        $orders = \App\Models\MonitoringOrder::with('orderPenjualan.details.master_produk')
            ->whereHas('orderPenjualan', function ($q) use ($tanggal) {
                $q->whereDate('tanggal_pembuatan', $tanggal);
            })
            ->when($kode, function($q) use ($kode) {
                $q->whereHas('orderPenjualan.details.master_produk', function($sub) use ($kode) {
                    $sub->where('kode', $kode);
                });
            })
            ->get();

        // REKAP: per kode, per nama produk, jumlah
        $rekap = [];
        foreach ($orders as $monitoring) {
            $namaPemesan = $monitoring->orderPenjualan->nama ?? '-';
            $jamPengiriman = $monitoring->jam_pengiriman ?? $monitoring->orderPenjualan->jam_pengiriman ?? '-';
            foreach ($monitoring->orderPenjualan->details as $detail) {
                $kodeProduk = $detail->master_produk->kode ?? '-';
                $namaProduk = $detail->master_produk->nama_produk ?? '-';
                if ($kode && $kodeProduk !== $kode) continue;
                $rekap[] = [
                    'kode_produk'     => $kodeProduk,
                    'nama_produk'     => $namaProduk,
                    'nama_pemesan'    => $namaPemesan,
                    'jumlah'          => $detail->jumlah_beli,
                    'jam_pengiriman'  => $jamPengiriman,
                    'keterangan'      => $monitoring->keterangan ?? $monitoring->orderPenjualan->keterangan ?? '-',
                    'keterangan_staf' => $monitoring->keterangan_staf ?? $monitoring->orderPenjualan->keterangan_staf ?? '-',
                ];
            }
        }

        // Tambahan: Hitung total per produk
        $totalPerProduk = [];
        foreach ($rekap as $row) {
            $key = $row['kode_produk'] . ' - ' . $row['nama_produk'];
            $totalPerProduk[$key] = ($totalPerProduk[$key] ?? 0) + $row['jumlah'];
        }

        if ($type === 'excel') {
            if (ob_get_contents()) ob_end_clean();
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\MonitoringOrderExport($rekap, $tanggal, $kode, $totalPerProduk),
                'monitoring-order-'.$tanggal.($kode ? '-'.$kode : '').'.xlsx'
            );
        }

        // Untuk PDF
        $view = view('exports.monitoring_order_pdf', [
            'rekap' => $rekap,
            'totalPerProduk' => $totalPerProduk,
            'tanggal' => $tanggal,
            'kode' => $kode,
        ])->render();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($view)->setPaper('A4', 'portrait');
        if (ob_get_contents()) ob_end_clean();
        return $pdf->download('monitoring-order-'.$tanggal.($kode ? '-'.$kode : '').'.pdf');
    }

    public function print(Request $request)
    {
        $tanggal = $request->input('tanggal') ?: now()->setTimezone('Asia/Jakarta')->toDateString();
        $kode = $request->input('kode');

        $orders = \App\Models\MonitoringOrder::with('orderPenjualan.details.master_produk')
            ->whereHas('orderPenjualan', function ($q) use ($tanggal) {
                $q->whereDate('tanggal_pembuatan', $tanggal);
            })
            ->when($kode, function($q) use ($kode) {
                $q->whereHas('orderPenjualan.details.master_produk', function($sub) use ($kode) {
                    $sub->where('kode', $kode);
                });
            })
            ->get();

        // REKAP: per kode, per nama produk, jumlah
        $rekap = [];
        foreach ($orders as $monitoring) {
            $namaPemesan = $monitoring->orderPenjualan->nama ?? '-';
            $jamPengiriman = $monitoring->jam_pengiriman ?? $monitoring->orderPenjualan->jam_pengiriman ?? '-';
            foreach ($monitoring->orderPenjualan->details as $detail) {
                $kodeProduk = $detail->master_produk->kode ?? '-';
                $namaProduk = $detail->master_produk->nama_produk ?? '-';
                if ($kode && $kodeProduk !== $kode) continue;
                $rekap[] = [
                    'kode_produk'     => $kodeProduk,
                    'nama_produk'     => $namaProduk,
                    'nama_pemesan'    => $namaPemesan,
                    'jumlah'          => $detail->jumlah_beli,
                    'jam_pengiriman'  => $jamPengiriman,
                    'keterangan'      => $monitoring->keterangan ?? $monitoring->orderPenjualan->keterangan ?? '-',
                    'keterangan_staf' => $monitoring->keterangan_staf ?? $monitoring->orderPenjualan->keterangan_staf ?? '-',
                ];
            }
        }

        // Tambahan: Hitung total per produk
        $totalPerProduk = [];
        foreach ($rekap as $row) {
            $key = $row['kode_produk'] . ' - ' . $row['nama_produk'];
            $totalPerProduk[$key] = ($totalPerProduk[$key] ?? 0) + $row['jumlah'];
        }

        return view('exports.monitoring_order_print', [
            'rekap' => $rekap,
            'totalPerProduk' => $totalPerProduk,
            'tanggal' => $tanggal,
            'kode' => $kode,
        ]);
    }
}

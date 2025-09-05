<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\MasterProduk;
use App\Models\MonitoringOrder;
use App\Models\OrderPenjualan;
use App\Models\MasterKendaraan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class MonitoringOrderController extends Controller
{
    public function index(Request $request)
{
    session(['monitoring_order_last_url' => $request->fullUrl()]);
    $search  = $request->search;
    $tanggal = $request->tanggal ?? now()->setTimezone('Asia/Jakarta')->toDateString();

    // Ambil monitoring orders sesuai filter (tanpa merubah status lama)
    $monitoringOrders = MonitoringOrder::with('orderPenjualan.details.master_produk')
        ->when($search, function ($q) use ($search) {
            $q->whereHas('orderPenjualan', fn($sub) =>
                $sub->where('nama', 'like', "%{$search}%")
            )->orWhereHas('orderPenjualan.details.master_produk', fn($sub2) =>
                $sub2->where('nama_produk', 'like', "%{$search}%")
                     ->orWhere('kode', 'like', "%{$search}%")
            );
        })
        ->when($tanggal, fn($q) => $q->whereHas('orderPenjualan', fn($sub) =>
            $sub->whereDate('tanggal_pembuatan', $tanggal)
        ))
        ->orderByDesc('id')
        ->paginate(100)
        ->appends($request->query());

    // Kumpulan kode & nama produk
    $produkKodes = MasterProduk::select('kode', 'nama_produk')
        ->groupBy('kode', 'nama_produk')
        ->get();

    // Rekap total jumlah per produk (untuk hari ini)
    $today = now()->setTimezone('Asia/Jakarta')->toDateString();
    $totalJumlahPerProduk = [];
    foreach ($monitoringOrders as $monitoring) {
        if ($monitoring->orderPenjualan->tanggal_pembuatan === $today) {
            foreach ($monitoring->orderPenjualan->details as $detail) {
                $kode = $detail->master_produk->kode ?? '-';
                $nama = $detail->master_produk->nama_produk ?? '-';
                $key  = "$nama ($kode)";
                $totalJumlahPerProduk[$key] = ($totalJumlahPerProduk[$key] ?? 0) + $detail->jumlah_beli;
            }
        }
    }

    $masterKendaraan = MasterKendaraan::where('status', 2)->get();

    return Inertia::render('Apps/MonitoringOrder/Index', [
        'monitoringOrders'     => $monitoringOrders,
        'produkKodes'          => $produkKodes,
        'totalJumlahPerProduk' => $totalJumlahPerProduk,
        'filters'              => [
            'search'  => $search,
            'tanggal' => $tanggal,
        ],
        'masterKendaraan'      => $masterKendaraan,
    ]);
}


    public function store(Request $request)
    {
        $request->validate([
            'order_penjualan_id' => 'required|exists:order_penjualan,id',
            'status_produksi'    => 'required|in:1,2',
        ]);

        $order = OrderPenjualan::findOrFail($request->order_penjualan_id);

        MonitoringOrder::updateOrCreate(
    ['order_penjualan_id' => $order->id],
    [
        'status_produksi'   => $request->status_produksi,
       
        'created_id'        => Auth::id(),
    ]
);

        return redirect(session('monitoring_order_last_url', route('apps.monitoringorder.index')))
            ->with('success', 'Monitoring order berhasil diperbarui.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status_produksi'   => 'required|in:1,2',
            'tanggal_pembuatan' => 'nullable|date',
            'jam_pengiriman'    => 'nullable|in:Pagi,Siang,Sore,Malam',
        ]);

        $monitoringOrder = MonitoringOrder::findOrFail($id);
$monitoringOrder->status_produksi = $request->status_produksi;

$monitoringOrder->save();


        return redirect(session('monitoring_order_last_url', route('apps.monitoringorder.index')))
            ->with('success', 'Monitoring order berhasil diperbarui.');
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
            'kode'   => $kode,
            'orders' => $orders,
        ]);
    }

    public function export(Request $request)
    {
        $tanggal = $request->input('tanggal') ?: now()->setTimezone('Asia/Jakarta')->toDateString();
        $type    = $request->input('type') ?? 'pdf';
        $kode    = $request->input('kode');

        $orders = MonitoringOrder::with('orderPenjualan.details.master_produk')
            ->whereHas('orderPenjualan', function ($q) use ($tanggal) {
                $q->whereDate('tanggal_pembuatan', $tanggal);
            })
            ->when($kode, function ($q) use ($kode) {
                $q->whereHas('orderPenjualan.details.master_produk', function ($sub) use ($kode) {
                    $sub->where('kode', $kode);
                });
            })
            ->get();

        // Rekap data
        $rekap = [];
        foreach ($orders as $monitoring) {
            $namaPemesan   = $monitoring->orderPenjualan->nama ?? '-';
            $jamPengiriman = $monitoring->jam_pengiriman ?? $monitoring->orderPenjualan->jam_pengiriman ?? '-';
            foreach ($monitoring->orderPenjualan->details as $detail) {
                $kodeProduk = $detail->master_produk->kode ?? '-';
                $namaProduk = $detail->master_produk->nama_produk ?? '-';
                if ($kode && $kodeProduk !== $kode) {
                    continue;
                }
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

        // Total per produk
        $totalPerProduk = [];
        foreach ($rekap as $row) {
            $key = $row['kode_produk'] . ' - ' . $row['nama_produk'];
            $totalPerProduk[$key] = ($totalPerProduk[$key] ?? 0) + $row['jumlah'];
        }

        if ($type === 'excel') {
            if (ob_get_length()) {
                ob_end_clean();
            }
            return Excel::download(
                new \App\Exports\MonitoringOrderExport($rekap, $tanggal, $kode, $totalPerProduk),
                'monitoring-order-' . $tanggal . ($kode ? '-' . $kode : '') . '.xlsx'
            );
        }

        // PDF
        $view = view('exports.monitoring_order_pdf', [
            'rekap'          => $rekap,
            'totalPerProduk' => $totalPerProduk,
            'tanggal'        => $tanggal,
            'kode'           => $kode,
        ])->render();

        $pdf = Pdf::loadHTML($view)->setPaper('A4', 'portrait');
        if (ob_get_length()) {
            ob_end_clean();
        }
        return $pdf->download('monitoring-order-' . $tanggal . ($kode ? '-' . $kode : '') . '.pdf');
    }

    public function print(Request $request)
    {
        $tanggal = $request->input('tanggal') ?: now()->setTimezone('Asia/Jakarta')->toDateString();
        $kode    = $request->input('kode');

        $orders = MonitoringOrder::with('orderPenjualan.details.master_produk')
            ->whereHas('orderPenjualan', function ($q) use ($tanggal) {
                $q->whereDate('tanggal_pembuatan', $tanggal);
            })
            ->when($kode, function ($q) use ($kode) {
                $q->whereHas('orderPenjualan.details.master_produk', function ($sub) use ($kode) {
                    $sub->where('kode', $kode);
                });
            })
            ->get();

        // Rekap
        $rekap = [];
        foreach ($orders as $monitoring) {
            $namaPemesan   = $monitoring->orderPenjualan->nama ?? '-';
            $jamPengiriman = $monitoring->jam_pengiriman ?? $monitoring->orderPenjualan->jam_pengiriman ?? '-';
            foreach ($monitoring->orderPenjualan->details as $detail) {
                $kodeProduk = $detail->master_produk->kode ?? '-';
                $namaProduk = $detail->master_produk->nama_produk ?? '-';
                if ($kode && $kodeProduk !== $kode) {
                    continue;
                }
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

        // Total per produk
        $totalPerProduk = [];
        foreach ($rekap as $row) {
            $key = $row['kode_produk'] . ' - ' . $row['nama_produk'];
            $totalPerProduk[$key] = ($totalPerProduk[$key] ?? 0) + $row['jumlah'];
        }

        return view('exports.monitoring_order_print', [
            'rekap'          => $rekap,
            'totalPerProduk' => $totalPerProduk,
            'tanggal'        => $tanggal,
            'kode'           => $kode,
        ]);
    }
}

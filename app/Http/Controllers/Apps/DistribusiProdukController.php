<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\DistribusiProduk;
use App\Models\MonitoringOrder;
use App\Models\MasterProduk;
use App\Models\MasterKendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class DistribusiProdukController extends Controller
{
    public function index()
{
    $search = request()->search;
    $tanggal = request()->tanggal;

    $monitoringOrders = MonitoringOrder::with([
        'orderPenjualan.details.master_produk',
        'distribusiProduk.master_kendaraan'
    ])
    ->where('status_produksi', 2)
    ->when($search, function ($q) use ($search) {
        $q->whereHas('orderPenjualan', function ($sub) use ($search) {
            $sub->where('nama', 'like', '%' . $search . '%');
        })->orWhereHas('orderPenjualan.details.master_produk', function ($sub2) use ($search) {
            $sub2->where('nama_produk', 'like', '%' . $search . '%')
                 ->orWhere('kode', 'like', '%' . $search . '%');
        });
    })
    ->when($tanggal, function ($q) use ($tanggal) {
        $q->whereHas('orderPenjualan', function ($sub) use ($tanggal) {
            $sub->whereDate('tanggal_pengiriman', $tanggal);
        });
    })
    ->orderBy('created_at', 'desc')
    ->paginate(10);

    $produkKodes = MasterProduk::select('kode', 'nama_produk')
        ->groupBy('kode', 'nama_produk')
        ->get();

    $kendaraanOptions = MasterKendaraan::select('id', 'kode_kendaraan', 'driver')->get();

    $totalJumlahPerProduk = [];
    foreach ($monitoringOrders as $monitoring) {
        foreach ($monitoring->orderPenjualan->details as $detail) {
            $kode = $detail->master_produk->kode ?? '-';
            $nama = $detail->master_produk->nama_produk ?? '-';
            $key = $kode . ' - ' . $nama;
            $totalJumlahPerProduk[$key] = ($totalJumlahPerProduk[$key] ?? 0) + $detail->jumlah_beli;
        }
    }

    return Inertia::render('Apps/DistribusiProduk/Index', [
        'distribusiProduks' => $monitoringOrders,
        'produkKodes' => $produkKodes,
        'kendaraanOptions' => $kendaraanOptions,
        'totalJumlahPerProduk' => $totalJumlahPerProduk,
        'filters' => [
            'search' => $search,
            'tanggal' => $tanggal,
        ],
    ]);
}


    public function store(Request $request)
    {
        $request->validate([
            'monitoring_order_id' => 'required|exists:monitoring_order,id',
            'status_distribusi' => 'required|in:1,2',
            'master_kendaraan_id' => 'required|exists:master_kendaraan,id',
            'tanggal_pengiriman' => 'nullable|date',
            'jam_pengiriman' => 'nullable',
        ]);

        $existing = DistribusiProduk::where('monitoring_order_id', $request->monitoring_order_id)->first();
        if ($existing) {
            return redirect()->route('apps.distribusiproduk.index')->with('message', 'Sudah didistribusikan.');
        }

        $monitoring = MonitoringOrder::findOrFail($request->monitoring_order_id);

        DistribusiProduk::create([
            'monitoring_order_id' => $monitoring->id,
            'order_penjualan_id' => $monitoring->order_penjualan_id,
            'master_kendaraan_id' => $request->master_kendaraan_id,
            'status_distribusi' => $request->status_distribusi,
            'tanggal_pengiriman' => $request->tanggal_pengiriman ?? $monitoring->tanggal_pengiriman,
            'jam_pengiriman' => $request->jam_pengiriman ?? $monitoring->jam_pengiriman,
            'created_id' => Auth::id(),
        ]);

        return redirect()->route('apps.distribusiproduk.index')->with('message', 'Distribusi berhasil dibuat.');
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'status_distribusi' => 'required|in:1,2',
        'master_kendaraan_id' => 'nullable|exists:master_kendaraan,id',
    ]);

    $data = DistribusiProduk::findOrFail($id);
    $data->status_distribusi = $request->status_distribusi;
    $data->master_kendaraan_id = $request->master_kendaraan_id;
    $data->save();

    // Jika status selesai distribusi, tambahkan stok ke kasir
    // Hanya jika kode_distribusi bukan PO-
    if ($data->status_distribusi == 2 && !str_starts_with($data->orderPenjualan->kode_distribusi, 'PO-')) {
        $order = $data->orderPenjualan;
        foreach ($order->details as $detail) {
            $stok = \App\Models\InventoryStok::firstOrCreate(
                ['master_produk_id' => $detail->master_produk_id],
                ['stok' => 0]
            );
            $stok->stok += $detail->jumlah_beli;
            $stok->save();
        }
    }

    return back()->with('success', 'Distribusi produk berhasil diperbarui.');
}

    public function orderDistribusi(Request $request)
    {
        $kode = $request->kode;

        $orders = DistribusiProduk::with('monitoringOrder.orderPenjualan.details.master_produk')
            ->whereHas('monitoringOrder.orderPenjualan.details.master_produk', function ($q) use ($kode) {
                $q->where('kode', $kode);
            })
            ->get();

        return Inertia::render('Apps/OrderDistribusi/Index', [
            'kode' => $kode,
            'orders' => $orders,
        ]);
    }

    public function exportSuratJalan(Request $request, $orderId)
{
    $order = \App\Models\OrderPenjualan::with(['details.master_produk'])->findOrFail($orderId);
    $kendaraan = null;
    // Cari kendaraan dari distribusi_produk
    $distribusi = \App\Models\DistribusiProduk::where('order_penjualan_id', $orderId)
        ->with('master_kendaraan')
        ->first();
    if ($distribusi) {
        $kendaraan = $distribusi->master_kendaraan;
    }

    $pdf = Pdf::loadView('exports.surat_jalan_pdf', [
        'order' => $order,
        'kendaraan' => $kendaraan,
    ])->setPaper('A4', 'portrait');

    return $pdf->download('surat-jalan-'.$order->nama.'-'.$order->id.'.pdf');
}
public function printSuratJalan($orderId)
{
    $order = \App\Models\OrderPenjualan::with(['details.master_produk'])->findOrFail($orderId);
    $distribusi = \App\Models\DistribusiProduk::where('order_penjualan_id', $orderId)
        ->with('master_kendaraan')
        ->first();

    return view('exports.surat_jalan_print', [
        'order' => $order,
        'kendaraan' => $distribusi->master_kendaraan ?? null,
        'distribusi' => $distribusi,
    ]);
}
public function listPurchaseOrder(Request $request)
{
    $search = $request->search;
    $tanggal = $request->tanggal;

    $monitoringOrders = \App\Models\MonitoringOrder::with([
        'orderPenjualan.details.master_produk',
        'distribusiProduk.master_kendaraan'
    ])
    ->whereHas('orderPenjualan', function ($q) {
        $q->where('kode_distribusi', 'like', 'PO-%');
    })
    ->whereHas('distribusiProduk', function ($q) {
        $q->where('status_distribusi', 2); // Selesai Distribusi
    })
    ->when($search, function ($q) use ($search) {
        $q->whereHas('orderPenjualan', function ($sub) use ($search) {
            $sub->where('nama', 'like', '%' . $search . '%');
        });
    })
    ->when($tanggal, function ($q) use ($tanggal) {
        $q->whereHas('orderPenjualan', function ($sub) use ($tanggal) {
            $sub->whereDate('tanggal_pengiriman', $tanggal);
        });
    })
    ->orderBy('created_at', 'desc')
    ->paginate(10);

    $kendaraanOptions = \App\Models\MasterKendaraan::select('id', 'kode_kendaraan', 'driver')->get();

    return Inertia::render('Apps/DistribusiProduk/ListPurchaseOrder', [
        'distribusiProduks' => $monitoringOrders,
        'kendaraanOptions' => $kendaraanOptions,
        'filters' => [
            'search' => $search,
            'tanggal' => $tanggal,
        ],
    ]);
}
}

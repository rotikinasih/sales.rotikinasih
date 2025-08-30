<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\MasterProduk;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Exports\MonitoringStokExport;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MonitoringStokController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    $tanggal = $request->tanggal ?? now()->toDateString();
    $outlet_id = $request->outlet_id ?? auth()->user()->outlets->first()->id ?? null;

    // Ambil semua outlet user untuk dropdown
    $outlets = auth()->user()->outlets()->get();

    // Ambil produk yang punya stok di outlet yang dipilih
    $produk = \App\Models\MasterProduk::whereHas('inventory_stok', function($q) use ($outlet_id) {
        $q->where('outlet_id', $outlet_id);
    })->with(['inventory_stok' => function($q) use ($outlet_id) {
        $q->where('outlet_id', $outlet_id);
    }])->get();

    $data = $produk->map(function ($prd) use ($tanggal, $outlet_id) {

        // --- Stok Masuk Hari Ini
        $stok_masuk = DB::table('distribusi_produk as dp')
            ->join('monitoring_order as mo', 'dp.monitoring_order_id', '=', 'mo.id')
            ->join('order_penjualan as op', 'mo.order_penjualan_id', '=', 'op.id')
            ->join('order_penjualan_details as opd', 'op.id', '=', 'opd.order_penjualan_id')
            ->where('dp.status_distribusi', 2)
            ->where('op.kode_distribusi', 'like', 'PO-%')
            ->where('opd.master_produk_id', $prd->id)
            ->whereDate('dp.updated_at', $tanggal)
            ->where('op.outlet_id', $outlet_id) // <-- filter outlet
            ->sum('opd.jumlah_beli');

        // --- Stok Terjual Hari Ini
        $stok_terjual = DB::table('transaksi_items as ti')
            ->join('transaksis as t', 'ti.transaksi_id', '=', 't.id')
            ->where('ti.master_produk_id', $prd->id)
            ->whereDate('t.created_at', $tanggal)
            ->where('t.outlet_id', $outlet_id) // <-- filter outlet
            ->sum('ti.jumlah');

        // --- Retur Hari Ini (PERBAIKI DI SINI)
        $retur = \App\Models\ReturProduk::where('produk_id', $prd->id)
            ->whereDate('tanggal', $tanggal)
            ->where('outlet_id', $outlet_id) // <-- filter outlet
            ->sum('jumlah');

        // --- Final Stok Hari Ini (stok real di inventory)
        $final_stok = $prd->inventory_stok->stok ?? 0;

        // --- Hitung Stok Awal (ambil dari final_stok hari ini dengan mundurkan perhitungan)
        $stok_awal = $final_stok - $stok_masuk + $stok_terjual + $retur;

        // --- Hitung nominal
        $harga_terjual = $stok_terjual * ($prd->outlet_price ?? 0);
        $harga_final   = $final_stok * ($prd->outlet_price ?? 0);

        return [
            'nama_produk'   => $prd->nama_produk,
            'harga'         => $prd->outlet_price ?? 0,
            'stok_awal'     => $stok_awal,
            'stok_masuk'    => $stok_masuk,
            'stok_terjual'  => $stok_terjual,
            'retur'         => $retur,
            'final_stok'    => $final_stok,
            'harga_terjual' => $harga_terjual,
            'harga_final'   => $harga_final,
        ];
    });

    $total_diskon = \App\Models\Transaksi::whereDate('created_at', $tanggal)
        ->where('outlet_id', $outlet_id) // <-- filter outlet
        ->sum('diskon');

    $total = [
        'stok_awal'     => $data->sum('stok_awal'),
        'stok_masuk'    => $data->sum('stok_masuk'),
        'stok_terjual'  => $data->sum('stok_terjual'),
        'retur'         => $data->sum('retur'),
        'final_stok'    => $data->sum('final_stok'),
        'harga_terjual' => $data->sum('harga_terjual'),
        'harga_final'   => $data->sum('harga_final'),
        'diskon'        => $total_diskon,
    ];

    return Inertia::render('Apps/MonitoringStok/Index', [
        'data' => $data,
        'total' => $total,
        'tanggal' => $tanggal,
        'outlets' => $outlets,
        'outlet_id' => $outlet_id,
    ]);
}


    public function export()
    {
        $tanggal = request()->tanggal ?? now()->toDateString();
        $outlet_id = request()->outlet_id ?? auth()->user()->outlets->first()->id ?? null;
        $type = request()->type ?? 'pdf';

        // Ambil produk yang punya stok di outlet yang dipilih
        $produk = \App\Models\MasterProduk::whereHas('inventory_stok', function($q) use ($outlet_id) {
            $q->where('outlet_id', $outlet_id);
        })->with(['inventory_stok' => function($q) use ($outlet_id) {
            $q->where('outlet_id', $outlet_id);
        }])->get();

        $data = $produk->map(function ($prd) use ($tanggal, $outlet_id) {
            $stok_masuk = DB::table('distribusi_produk as dp')
                ->join('monitoring_order as mo', 'dp.monitoring_order_id', '=', 'mo.id')
                ->join('order_penjualan as op', 'mo.order_penjualan_id', '=', 'op.id')
                ->join('order_penjualan_details as opd', 'op.id', '=', 'opd.order_penjualan_id')
                ->where('dp.status_distribusi', 2)
                ->where('op.kode_distribusi', 'like', 'PO-%')
                ->where('opd.master_produk_id', $prd->id)
                ->whereDate('dp.updated_at', $tanggal)
                ->where('op.outlet_id', $outlet_id)
                ->sum('opd.jumlah_beli');

            $stok_terjual = DB::table('transaksi_items as ti')
                ->join('transaksis as t', 'ti.transaksi_id', '=', 't.id')
                ->where('ti.master_produk_id', $prd->id)
                ->whereDate('t.created_at', $tanggal)
                ->where('t.outlet_id', $outlet_id)
                ->sum('ti.jumlah');

            $retur = \App\Models\ReturProduk::where('produk_id', $prd->id)
                ->whereDate('tanggal', $tanggal)
                ->where('outlet_id', $outlet_id)
                ->sum('jumlah');

            $final_stok = $prd->inventory_stok->stok ?? 0;
            $stok_awal = $final_stok - $stok_masuk + $stok_terjual + $retur;
            $harga_terjual = $stok_terjual * ($prd->outlet_price ?? 0);
            $harga_final = $final_stok * ($prd->outlet_price ?? 0);

            return [
                'nama_produk' => $prd->nama_produk,
                'harga' => $prd->outlet_price ?? 0,
                'stok_awal' => $stok_awal,
                'stok_masuk' => $stok_masuk,
                'stok_terjual' => $stok_terjual,
                'retur' => $retur,
                'final_stok' => $final_stok,
                'harga_terjual' => $harga_terjual,
                'harga_final' => $harga_final,
            ];
        });

        $total = [
            'stok_awal' => $data->sum('stok_awal'),
            'harga_terjual' => $data->sum('harga_terjual'),
            'harga_final' => $data->sum('harga_final'),
            'stok_masuk' => $data->sum('stok_masuk'),
            'stok_terjual' => $data->sum('stok_terjual'),
            'retur' => $data->sum('retur'),
            'final_stok' => $data->sum('final_stok'),
        ];

        if ($type === 'excel') {
            if (ob_get_contents()) ob_end_clean();
            return \Excel::download(new \App\Exports\MonitoringStokExport($data, $total, $tanggal), 'monitoring-stok-'.$tanggal.'.xlsx');
        }

        $view = view('exports.monitoring_stok_pdf', [
            'data' => $data,
            'total' => $total,
            'tanggal' => $tanggal,
        ])->render();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($view)->setPaper('A4', 'landscape');
        if (ob_get_contents()) ob_end_clean();

        return $pdf->download('monitoring-stok-'.$tanggal.'.pdf');
    }

    public function print()
    {
        $tanggal = request()->tanggal ?? now()->toDateString();
        $outlet_id = request()->outlet_id ?? auth()->user()->outlets->first()->id ?? null;

        $produk = \App\Models\MasterProduk::whereHas('inventory_stok', function($q) use ($outlet_id) {
            $q->where('outlet_id', $outlet_id);
        })->with(['inventory_stok' => function($q) use ($outlet_id) {
            $q->where('outlet_id', $outlet_id);
        }])->get();

        $data = $produk->map(function ($prd) use ($tanggal, $outlet_id) {
            $stok_masuk = DB::table('distribusi_produk as dp')
                ->join('monitoring_order as mo', 'dp.monitoring_order_id', '=', 'mo.id')
                ->join('order_penjualan as op', 'mo.order_penjualan_id', '=', 'op.id')
                ->join('order_penjualan_details as opd', 'op.id', '=', 'opd.order_penjualan_id')
                ->where('dp.status_distribusi', 2)
                ->where('op.kode_distribusi', 'like', 'PO-%')
                ->where('opd.master_produk_id', $prd->id)
                ->whereDate('dp.updated_at', $tanggal)
                ->where('op.outlet_id', $outlet_id)
                ->sum('opd.jumlah_beli');

            $stok_terjual = DB::table('transaksi_items as ti')
                ->join('transaksis as t', 'ti.transaksi_id', '=', 't.id')
                ->where('ti.master_produk_id', $prd->id)
                ->whereDate('t.created_at', $tanggal)
                ->where('t.outlet_id', $outlet_id)
                ->sum('ti.jumlah');

            $retur = \App\Models\ReturProduk::where('produk_id', $prd->id)
                ->whereDate('tanggal', $tanggal)
                ->where('outlet_id', $outlet_id)
                ->sum('jumlah');

            $final_stok = $prd->inventory_stok->stok ?? 0;
            $stok_awal = $final_stok - $stok_masuk + $stok_terjual + $retur;
            $harga_terjual = $stok_terjual * ($prd->outlet_price ?? 0);
            $harga_final = $final_stok * ($prd->outlet_price ?? 0);

            return [
                'nama_produk' => $prd->nama_produk,
                'harga' => $prd->outlet_price ?? 0,
                'stok_awal' => $stok_awal,
                'stok_masuk' => $stok_masuk,
                'stok_terjual' => $stok_terjual,
                'retur' => $retur,
                'final_stok' => $final_stok,
                'harga_terjual' => $harga_terjual,
                'harga_final' => $harga_final,
            ];
        });

        $total = [
            'stok_awal' => $data->sum('stok_awal'),
            'harga_terjual' => $data->sum('harga_terjual'),
            'harga_final' => $data->sum('harga_final'),
            'stok_masuk' => $data->sum('stok_masuk'),
            'stok_terjual' => $data->sum('stok_terjual'),
            'retur' => $data->sum('retur'),
            'final_stok' => $data->sum('final_stok'),
        ];

        return view('exports.monitoring_stok_print', [
            'data' => $data,
            'total' => $total,
            'tanggal' => $tanggal,
        ]);
    }
}

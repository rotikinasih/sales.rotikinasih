<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\MasterProduk;
use App\Models\OrderPenjualan;
use App\Models\Transaksi;
use App\Models\MasterOutlet;
use App\Models\MasterKendaraan;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        // Ambil outlet_id yang dimiliki user login
        $userOutletIds = auth()->user()->outlets->pluck('id')->toArray();

        // Total produk
        $total_produk = MasterProduk::count();

        // Total order penjualan
        $total_order = OrderPenjualan::count();

        // Total outlet
        $total_outlet = MasterOutlet::count();

        // Total kendaraan
        $total_kendaraan = MasterKendaraan::count();

        // Total transaksi kasir per bulan (grafik)
        $trx_per_tanggal = \App\Models\Transaksi::select(
    DB::raw("DATE(created_at) as tanggal"),
    DB::raw("SUM(total) as total")
)
->whereYear('created_at', now()->year) // hanya tahun ini
->groupBy('tanggal')
->orderBy('tanggal', 'asc')
->get()
->filter(function($trx) {
    // Pastikan hanya tanggal yang benar-benar ada di database
    return $trx->total > 0;
})
->values();
        // Produk terlaris
        $produk_terlaris = DB::table('transaksi_items')
            ->join('transaksis', 'transaksi_items.transaksi_id', '=', 'transaksis.id')
            ->whereYear('transaksis.created_at', now()->year)
            ->select('transaksi_items.master_produk_id', DB::raw('SUM(transaksi_items.jumlah) as total_jual'))
            ->groupBy('transaksi_items.master_produk_id')
            ->orderByDesc('total_jual')
            ->limit(5)
            ->get()
            ->map(function($row) {
                $produk = \App\Models\MasterProduk::find($row->master_produk_id);
                return [
                    'nama_produk' => $produk ? $produk->nama_produk : '-',
                    'total_jual' => $row->total_jual
                ];
            });
$total_transaksi_tahun = \App\Models\Transaksi::whereYear('created_at', now()->year)->sum('total');

        // Omset tahun ini dari order penjualan (jumlah_bayar)
        $total_omset_tahun = \App\Models\OrderPenjualan::whereYear('tanggal_pembuatan', now()->year)->sum('jumlah_bayar');

        // Grafik omset per bulan dari order penjualan
        $omset_per_bulan = \App\Models\OrderPenjualan::select(
            DB::raw("DATE_FORMAT(tanggal_pembuatan, '%Y-%m') as bulan"),
            DB::raw("SUM(jumlah_bayar) as total")
        )
        ->whereYear('tanggal_pembuatan', now()->year)
        ->groupBy('bulan')
        ->orderBy('bulan', 'asc')
        ->get();

        // Produk pesanan terlaris dari order penjualan detail
        $produk_pesanan_terlaris = DB::table('order_penjualan_details')
            ->join('order_penjualan', 'order_penjualan_details.order_penjualan_id', '=', 'order_penjualan.id')
            ->whereYear('order_penjualan.tanggal_pembuatan', now()->year)
            ->select('order_penjualan_details.master_produk_id', DB::raw('SUM(order_penjualan_details.jumlah_beli) as total_jual'))
            ->groupBy('order_penjualan_details.master_produk_id')
            ->orderByDesc('total_jual')
            ->limit(5)
            ->get()
            ->map(function($row) {
                $produk = \App\Models\MasterProduk::find($row->master_produk_id);
                return [
                    'nama_produk' => $produk ? $produk->nama_produk : '-',
                    'total_jual' => $row->total_jual
                ];
            });

        return Inertia::render('Apps/Dashboard/Index', [
            'total_produk' => $total_produk,
            'total_order' => $total_order,
            'total_outlet' => $total_outlet,
            'total_kendaraan' => $total_kendaraan,
            'trx_per_tanggal' => $trx_per_tanggal,
            'produk_terlaris' => $produk_terlaris,
            'total_transaksi_tahun' => $total_transaksi_tahun,
            'total_omset_tahun' => $total_omset_tahun,
            'omset_per_bulan' => $omset_per_bulan,
            'produk_pesanan_terlaris' => $produk_pesanan_terlaris,
        ]);
    }
}

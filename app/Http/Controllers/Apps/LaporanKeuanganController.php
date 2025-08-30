<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\ReturProduk;
use App\Models\OrderPenjualan;
use App\Models\DistribusiProduk;
use Carbon\Carbon;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanKeuanganExport;

class LaporanKeuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal') ?? now()->toDateString();
        $outlet_id = $request->input('outlet_id') ?? auth()->user()->outlets->first()->id ?? null;

        $outlets = auth()->user()->outlets()->get();

        // DP hari ini: order belum lunas, metode pembayaran terisi, tanggal sama
        $total_dp = \App\Models\OrderPenjualan::whereDate('created_at', $tanggal)
            ->when($outlet_id && $outlet_id != 0, function($q) use ($outlet_id) {
                $q->where('outlet_id', $outlet_id);
            })
            ->whereNotNull('metode_pembayaran')
            ->where('metode_pembayaran', '!=', '')
            ->whereColumn('jumlah_bayar', '<', 'total_bayar')
            ->sum('jumlah_bayar');

        // Pelunasan hari ini: order sudah lunas, metode pembayaran terisi, tanggal sama
        $total_lunas = \App\Models\OrderPenjualan::whereDate('created_at', $tanggal)
            ->when($outlet_id && $outlet_id != 0, function($q) use ($outlet_id) {
                $q->where('outlet_id', $outlet_id);
            })
            ->where('status_pembayaran', 2) // Lunas
            ->whereNotNull('metode_pembayaran')
            ->where('metode_pembayaran', '!=', '')
            ->whereColumn('jumlah_bayar', '>=', 'total_bayar')
            ->sum('total_bayar');

        // Omset hari ini (total nominal terjual)
        $omset = Transaksi::whereDate('created_at', $tanggal)
            ->when($outlet_id && $outlet_id != 0, function($q) use ($outlet_id) {
                $q->where('outlet_id', $outlet_id);
            })
            ->sum('jumlah_bayar');

        // Biaya retur hari ini
        $biaya_retur = ReturProduk::whereDate('created_at', $tanggal)
            ->when($outlet_id && $outlet_id != 0, function($q) use ($outlet_id) {
                $q->where('outlet_id', $outlet_id);
            })
            ->sum('total');

        // Biaya stok datang (total harga dari distribusi produk PO yang diterima hari ini)
        $biaya_stok_datang = \App\Models\MonitoringOrder::with([
                'orderPenjualan.details.master_produk',
                'distribusiProduk.master_kendaraan'
            ])
            ->whereHas('orderPenjualan', function ($q) use ($tanggal, $outlet_id) {
                $q->where('kode_distribusi', 'like', 'PO-%');
                $q->whereDate('tanggal_pengiriman', $tanggal);
                if ($outlet_id && $outlet_id != 0) {
                    $q->where('outlet_id', $outlet_id);
                }
            })
            ->whereHas('distribusiProduk', function ($q) {
                $q->where('status_distribusi', 2);
            })
            ->get()
            ->flatMap(function($mo) {
                return $mo->orderPenjualan->details->map(function($d) {
                    return ($d->jumlah_beli ?? 0) * ($d->master_produk->outlet_price ?? 0);
                });
            })->sum();

        // Total diskon hari ini
        $total_diskon = Transaksi::whereDate('created_at', $tanggal)
            ->when($outlet_id && $outlet_id != 0, function($q) use ($outlet_id) {
                $q->where('outlet_id', $outlet_id);
            })
            ->sum('diskon');

        // Hitung total modal produk terjual hari ini
        $total_modal_terjual = Transaksi::whereDate('created_at', $tanggal)
            ->when($outlet_id && $outlet_id != 0, function($q) use ($outlet_id) {
                $q->where('outlet_id', $outlet_id);
            })
            ->with('items.produk')
            ->get()
            ->flatMap(function($trx) {
                return $trx->items->map(function($item) {
                    return ($item->jumlah ?? 0) * ($item->produk->harga_modal ?? 0);
                });
            })->sum();

        // Ambil pesanan (OrderPenjualan) hari ini
        $pesanan = OrderPenjualan::with('details.master_produk')
            ->whereDate('created_at', $tanggal)
            ->when($outlet_id && $outlet_id != 0, function($q) use ($outlet_id) {
                $q->where('outlet_id', $outlet_id);
            })
            ->get();

        // Total biaya pesanan masuk (total_bayar)
        $total_biaya_pesanan = $pesanan->sum('total_bayar');

        // Total modal pesanan masuk
        $total_modal_pesanan = $pesanan->flatMap(function($order) {
            return $order->details->map(function($detail) {
                return ($detail->jumlah_beli ?? 0) * ($detail->master_produk->harga_modal ?? 0);
            });
        })->sum();

        // Kirim ke frontend
        return Inertia::render('Apps/LaporanKeuangan/Index', [
            'tanggal' => $tanggal,
            'outlets' => $outlets,
            'outlet_id' => $outlet_id,
            'omset' => (float) $omset,
            'biaya_retur' => (float) $biaya_retur,
            'biaya_stok_datang' => (float) $biaya_stok_datang,
            'total_dp' => (float) $total_dp,
            'total_lunas' => (float) $total_lunas,
            'total_diskon' => (float) $total_diskon,
            'total_modal_terjual' => (float) $total_modal_terjual,
            'total_biaya_pesanan' => (float) $total_biaya_pesanan,
            'total_modal_pesanan' => (float) $total_modal_pesanan,
        ]);
    }

    public function export(Request $request)
    {
        $tanggal = $request->input('tanggal') ?? now()->toDateString();
        $outlet_id = $request->input('outlet_id') ?? auth()->user()->outlets->first()->id ?? null;
        $type = $request->input('type') ?? 'pdf';

        // Ambil data sama seperti index()
        $data = $this->getLaporanKeuanganData($tanggal, $outlet_id);

        if ($type === 'excel') {
            if (ob_get_contents()) ob_end_clean();
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\LaporanKeuanganExport($data, $tanggal, $outlet_id), // <-- tambahkan $outlet_id
                'laporan-keuangan-'.$tanggal.'.xlsx'
            );
        }

        $view = view('exports.laporan_keuangan_pdf', [
            'data' => $data,
            'tanggal' => $tanggal,
            'outlet_id' => $outlet_id, // <-- tambahkan ini
        ])->render();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($view)->setPaper('A4', 'landscape');
        if (ob_get_contents()) ob_end_clean();

        return $pdf->download('laporan-keuangan-'.$tanggal.'.pdf');
    }

    public function print(Request $request)
    {
        $tanggal = $request->input('tanggal') ?? now()->toDateString();
        $outlet_id = $request->input('outlet_id') ?? auth()->user()->outlets->first()->id ?? null;

        $data = $this->getLaporanKeuanganData($tanggal, $outlet_id);

        return view('exports.laporan_keuangan_print', [
            'data' => $data,
            'tanggal' => $tanggal,
            'outlet_id' => $outlet_id,
        ]);
    }

    // Helper untuk ambil data laporan keuangan
    private function getLaporanKeuanganData($tanggal, $outlet_id)
    {
        // Ambil pesanan (OrderPenjualan) hari ini
        $pesanan = \App\Models\OrderPenjualan::with('details.master_produk')
            ->whereDate('created_at', $tanggal)
            ->when($outlet_id && $outlet_id != 0, function($q) use ($outlet_id) {
                $q->where('outlet_id', $outlet_id);
            })
            ->get();

        return [
            'omset' => Transaksi::whereDate('created_at', $tanggal)
                ->when($outlet_id && $outlet_id != 0, function($q) use ($outlet_id) {
                    $q->where('outlet_id', $outlet_id);
                })
                ->sum('jumlah_bayar'),
            'biaya_retur' => ReturProduk::whereDate('created_at', $tanggal)
                ->when($outlet_id && $outlet_id != 0, function($q) use ($outlet_id) {
                    $q->where('outlet_id', $outlet_id);
                })
                ->sum('total'),
            'biaya_stok_datang' => \App\Models\MonitoringOrder::with([
                    'orderPenjualan.details.master_produk',
                    'distribusiProduk.master_kendaraan'
                ])
                ->whereHas('orderPenjualan', function ($q) use ($tanggal, $outlet_id) {
                    $q->where('kode_distribusi', 'like', 'PO-%');
                    $q->whereDate('tanggal_pengiriman', $tanggal);
                    if ($outlet_id && $outlet_id != 0) {
                        $q->where('outlet_id', $outlet_id);
                    }
                })
                ->whereHas('distribusiProduk', function ($q) {
                    $q->where('status_distribusi', 2); // Selesai Distribusi
                })
                ->get()
                ->flatMap(function($mo) {
                    return $mo->orderPenjualan->details->map(function($d) {
                        return ($d->jumlah_beli ?? 0) * ($d->master_produk->outlet_price ?? 0);
                    });
                })->sum(),
            'total_dp' => \App\Models\OrderPenjualan::whereDate('created_at', $tanggal)
                ->when($outlet_id && $outlet_id != 0, function($q) use ($outlet_id) {
                    $q->where('outlet_id', $outlet_id);
                })
                ->whereNotNull('metode_pembayaran')
                ->where('metode_pembayaran', '!=', '')
                ->whereColumn('jumlah_bayar', '<', 'total_bayar')
                ->sum('jumlah_bayar'),
            'total_lunas' => OrderPenjualan::whereDate('created_at', $tanggal)
                ->when($outlet_id && $outlet_id != 0, function($q) use ($outlet_id) {
                    $q->where('outlet_id', $outlet_id);
                })
                ->where('status_pembayaran', 2)
                ->whereNotNull('metode_pembayaran')
                ->where('metode_pembayaran', '!=', '')
                ->whereColumn('jumlah_bayar', '>=', 'total_bayar')
                ->sum('total_bayar'),
            'total_diskon' => Transaksi::whereDate('created_at', $tanggal)
                ->when($outlet_id && $outlet_id != 0, function($q) use ($outlet_id) {
                    $q->where('outlet_id', $outlet_id);
                })
                ->sum('diskon'),
            'total_transaksi' => Transaksi::whereDate('created_at', $tanggal)
                ->when($outlet_id && $outlet_id != 0, function($q) use ($outlet_id) {
                    $q->where('outlet_id', $outlet_id);
                })
                ->count(),
            'total_retur' => ReturProduk::whereDate('created_at', $tanggal)
                ->when($outlet_id && $outlet_id != 0, function($q) use ($outlet_id) {
                    $q->where('outlet_id', $outlet_id);
                })
                ->count(),
            'total_pesanan' => OrderPenjualan::whereDate('created_at', $tanggal)
                ->when($outlet_id && $outlet_id != 0, function($q) use ($outlet_id) {
                    $q->where('outlet_id', $outlet_id);
                })
                ->count(),
            'total_modal_terjual' => \App\Models\Transaksi::whereDate('created_at', $tanggal)
                ->when($outlet_id && $outlet_id != 0, function($q) use ($outlet_id) {
                    $q->where('outlet_id', $outlet_id);
                })
                ->with('items.produk')
                ->get()
                ->flatMap(function($trx) {
                    return $trx->items->map(function($item) {
                        return ($item->jumlah ?? 0) * ($item->produk->harga_modal ?? 0);
                    });
                })->sum(),
            'total_biaya_pesanan' => $pesanan->sum('total_bayar'),
            'total_modal_pesanan' => $pesanan->flatMap(function($order) {
                return $order->details->map(function($detail) {
                    return ($detail->jumlah_beli ?? 0) * ($detail->master_produk->harga_modal ?? 0);
                });
            })->sum(),
        ];
    }
}

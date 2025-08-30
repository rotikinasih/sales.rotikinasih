<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\InventoryStok;
use App\Models\MasterProduk;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\RekapPenjualanExport;
use Dompdf\Dompdf;

class KasirController extends Controller
{
    public function index(Request $request)
    {
        $outlet_id = $request->outlet_id ?? auth()->user()->outlets->first()->id ?? null;
        $outlets = auth()->user()->outlets()->get();

        // Ambil produk dan stok hanya untuk outlet yang dipilih
        $produk = MasterProduk::with(['inventory_stok' => function($q) use ($outlet_id) {
            $q->where('outlet_id', $outlet_id);
        }])->get();

        $kategoriList = MasterProduk::select('kode')->distinct()->get();

        return Inertia::render('Apps/Kasir/Index', [
            'produk' => $produk,
            'kategoriList' => $kategoriList,
            'outlets' => $outlets,
            'outlet_id' => $outlet_id,
        ]);
    }

    public function tambahStok(Request $request)
    {
        $request->validate([
            'master_produk_id' => 'required|exists:master_produk,id',
            'jumlah_beli' => 'required|integer|min:1',
            'outlet_id' => 'required|exists:master_outlet,id', // <-- tambahkan validasi outlet
        ]);

        $stok = InventoryStok::firstOrCreate(
            [
                'master_produk_id' => $request->master_produk_id,
                'outlet_id' => $request->outlet_id // <-- tambahkan outlet_id
            ],
            ['stok' => 0]
        );

        $stok->stok += $request->jumlah_beli;
        $stok->save();

        return back()->with('success', 'Stok berhasil ditambah.');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'keranjang' => 'required|array|min:1',
            'keranjang.*.id' => 'required|exists:master_produk,id',
            'keranjang.*.jumlah' => 'required|integer|min:1',
            'keranjang.*.harga_jual' => 'required|numeric|min:0',
            'pembayaran' => 'required|in:cash,bank',
            'jumlah_bayar' => 'nullable|numeric',
            'pelanggan' => 'nullable|string|max:255',
            'diskon' => 'nullable|numeric|min:0',
        ]);

        $outletId = auth()->user()->outlets()->first()->id ?? null;
        if (!$outletId) {
            return back()->withErrors(['outlet_id' => 'User belum terhubung ke outlet manapun!']);
        }

        DB::beginTransaction();
        try {
            $totalAwal = collect($request->keranjang)->sum(fn($item) => $item['jumlah'] * $item['harga_jual']);
            $diskon = $request->diskon ?? 0;

            // Simpan diskon ke database terlebih dahulu
            $transaksi = Transaksi::create([
                'kode_transaksi' => 'TRX-' . now()->format('YmdHis'),
                'total' => $totalAwal, // simpan total awal dulu
                'pembayaran' => $request->pembayaran,
                'jumlah_bayar' => $request->jumlah_bayar,
                'pelanggan' => $request->pelanggan,
                'diskon' => $diskon,
                'outlet_id' => $outletId,
            ]);

            foreach ($request->keranjang as $item) {
                $produk = MasterProduk::findOrFail($item['id']);
                $stok = InventoryStok::firstOrCreate(
                    [
                        'master_produk_id' => $produk->id,
                        'outlet_id' => $outletId
                    ],
                    ['stok' => 0]
                );

                if ($stok->stok < $item['jumlah']) {
                    throw new \Exception("Stok tidak cukup untuk produk: {$produk->nama_produk}");
                }

                TransaksiItem::create([
                    'transaksi_id' => $transaksi->id,
                    'master_produk_id' => $produk->id,
                    'jumlah' => $item['jumlah'],
                    'harga_jual' => $item['harga_jual'],
                ]);

                $stok->stok -= $item['jumlah'];
                $stok->save();
            }

            // Setelah diskon tersimpan, update total transaksi
            $totalAkhir = max(0, $transaksi->total - $diskon);
            $transaksi->total = $totalAkhir;
            $transaksi->save();

            DB::commit();
            return back()->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['message' => 'Gagal transaksi: ' . $e->getMessage()]);
        }
    }

    public function editStok(Request $request)
    {
        $request->validate([
            'master_produk_id' => 'required|exists:master_produk,id',
            'stok' => 'required|integer|min:0',
        ]);

        $stok = InventoryStok::firstOrCreate(['master_produk_id' => $request->master_produk_id]);
        $stok->stok = $request->stok;
        $stok->save();

        return back()->with('success', 'Stok berhasil diupdate.');
    }

    public function rekap(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->toDateString();

        $userOutletIds = auth()->user()->outlets->pluck('id')->toArray();

        // Ambil transaksi kasir hanya untuk outlet user
        $transaksis = \App\Models\Transaksi::with('items.produk')
            ->whereDate('created_at', $tanggal)
            ->whereIn('outlet_id', $userOutletIds)
            ->get()
            ->map(function($trx) {
                return [
                    'kode_transaksi' => $trx->kode_transaksi,
                    'created_at' => $trx->created_at,
                    'pembayaran' => $trx->pembayaran,
                    'total' => $trx->total,
                    'diskon' => $trx->diskon,
                    'jumlah_bayar' => $trx->jumlah_bayar, // <-- tambahkan ini
                    'items' => $trx->items,
                    'jenis_penjualan' => 'Reguler',
                ];
            });

        // Ambil order penjualan hanya untuk outlet user dan metode_pembayaran tidak null/kosong
        $orderPenjualan = \App\Models\OrderPenjualan::with('details.master_produk')
            ->whereDate('created_at', $tanggal)
            ->whereIn('outlet_id', $userOutletIds)
            ->whereNotNull('metode_pembayaran')
            ->where('metode_pembayaran', '!=', '')
            ->get()
            ->map(function($order) {
                $kode = 'TRX-' . \Carbon\Carbon::parse($order->created_at)->format('YmdHis');
                return [
                    'kode_transaksi' => $kode,
                    'created_at' => $order->created_at,
                    'pembayaran' => $order->metode_pembayaran ?? '-',
                    'total' => $order->total_bayar,
                    'diskon' => 0, // order penjualan tidak ada diskon
                    'items' => $order->details->map(function($d) {
                        return [
                            'produk' => $d->master_produk,
                            'jumlah' => $d->jumlah_beli,
                            'harga_jual' => $d->master_produk->outlet_price ?? 0,
                        ];
                    }),
                    'jenis_penjualan' => 'Pesanan',
                ];
            });

        $allTransaksi = $transaksis->concat($orderPenjualan)->sortBy('created_at')->values();
        $total = $allTransaksi->sum('total');
        $total_diskon = $transaksis->sum('diskon');

        return Inertia::render('Apps/Kasir/Rekap', [
            'transaksis' => $allTransaksi,
            'tanggal' => $tanggal,
            'total' => $total,
            'total_diskon' => $total_diskon,
        ]);
    }

    public function export(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->toDateString();
        $type = $request->type ?? 'pdf';

        $transaksis = Transaksi::with('items.produk')
            ->whereDate('created_at', $tanggal)
            ->get();

        if ($type === 'excel') {
            // Export Excel dengan response biasa
            $export = new RekapPenjualanExport($transaksis, $tanggal);
            if (ob_get_contents()) ob_end_clean();

            return \Maatwebsite\Excel\Facades\Excel::download($export, 'rekap-penjualan-' . $tanggal . '.xlsx');
        }

        if ($type === 'pdf') {
    $view = view('exports.rekap_pdf', [
        'transaksis' => $transaksis,
        'tanggal' => $tanggal,
        'total' => $transaksis->sum('total'),
    ])->render();

    $dompdf = new Dompdf();
    $dompdf->loadHtml($view);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // 🔥 Bersihkan buffer output sebelum kirim file
    if (ob_get_contents()) ob_end_clean();

    return response($dompdf->output(), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="rekap-penjualan-' . $tanggal . '.pdf"');
}


        return back()->withErrors(['message' => 'Format export tidak dikenali.']);
    }
}

<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\OrderPenjualan;
use App\Models\OrderPenjualanDetail;
use App\Models\MasterProduk;
use App\Models\MasterOutlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Inertia\Inertia;

class OrderPenjualanController extends Controller
{
    public function index()
    {
        $userOutletIds = auth()->user()->outlets->pluck('id')->toArray();
        $search = request()->search; // bukan searchNama
        $searchTanggalInput = request()->searchTanggalInput;
        $searchTanggalPembuatan = request()->searchTanggalPembuatan;
        $searchTanggalPengiriman = request()->searchTanggalPengiriman;
        $searchStatusPembayaran = request()->searchStatusPembayaran;
        $sort = request()->sort ?? 'id';

        $orderpenjualan = OrderPenjualan::with(['details.master_produk', 'cicilan'])
            ->whereIn('outlet_id', $userOutletIds)
            ->when($search, fn($q) =>
                $q->where(function($qq) use ($search) {
                    $qq->where('nama', 'like', "%$search%")
                       ->orWhere('alamat_pengiriman', 'like', "%$search%")
                       ->orWhere('no_fraktur', 'like', "%$search%")
                       ->orWhere('penginput', 'like', "%$search%")
                       ->orWhere('lokasi', 'like', "%$search%");
                })
            )
            ->when($searchTanggalInput, fn($q) =>
                $q->whereDate('created_at', $searchTanggalInput)
            )
            ->when($searchTanggalPembuatan, fn($q) =>
                $q->whereDate('tanggal_pembuatan', $searchTanggalPembuatan)
            )
            ->when($searchTanggalPengiriman, fn($q) =>
                $q->whereDate('tanggal_pengiriman', $searchTanggalPengiriman)
            )
            ->when($searchStatusPembayaran, function($q) use ($searchStatusPembayaran) {
                if ($searchStatusPembayaran == 3) {
                    // Lunas dengan riwayat DP
                    $q->where('status_pembayaran', 2)
                      ->whereHas('cicilan');
                } else {
                    $q->where('status_pembayaran', $searchStatusPembayaran);
                }
            })
            ->orderBy($sort, request()->order ?? 'desc')
            ->paginate(25)
            ->appends(request()->query()); // <-- tambahkan ini agar query string tetap

        foreach ($orderpenjualan as $order) {
            // Ambil tanggal dari cicilan pertama jika ada
            $order->tanggal_dp = $order->cicilan->count() > 0 ? $order->cicilan->first()->tanggal : null;
            // Jika ingin array semua tanggal cicilan:
            $order->tanggal_dp_list = $order->cicilan->pluck('tanggal')->toArray();
        }

        $produk = MasterProduk::all();
        $masterOutlet = MasterOutlet::where('status', 1)->get();
        $masterCS = \App\Models\MasterCS::where('status', 1)->get();

        return Inertia::render('Apps/orderpenjualan/Index', [
            'orderpenjualan' => $orderpenjualan,
            'produk' => $produk,
            'masterOutlet' => $masterOutlet,
            'masterCS' => $masterCS, // <-- tambahkan ini
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'produkList' => 'required|array|min:1',
            'produkList.*.master_produk_id' => 'required|exists:master_produk,id',
            'produkList.*.jumlah_beli' => 'required|integer|min:1',
            'tanggal_pengiriman' => 'nullable|date',
            'jam_pengiriman' => 'nullable|in:Pagi,Siang,Sore,Malam',
            'tanggal_pembuatan' => 'nullable|date',
            'outlet_id' => 'required|exists:master_outlet,id', // <-- tambahkan validasi outlet_id
        ]);

        DB::beginTransaction();

        try {
            // Ambil outlet
            $outlet = \App\Models\MasterOutlet::find($request->outlet_id);
            // Buat singkatan outlet (ambil 2 huruf pertama, uppercase)
            $singkatanOutlet = $outlet && $outlet->lokasi ? strtoupper(substr(preg_replace('/\s+/', '', $outlet->lokasi), 0, 2)) : 'XX';
            // Kode distribusi
            $kodeDistribusi = 'CUST-' . $singkatanOutlet . '-' . now()->format('YmdHis');
            $last = OrderPenjualan::orderBy('id', 'desc')->first();
            $noFraktur = 'OP' . str_pad(($last ? $last->id + 1 : 1), 6, '0', STR_PAD_LEFT);

            if ($request->lokasi == 'INTERNAL' || $request->status_pembayaran == 2) {
                $jumlah_bayar = $request->total_bayar;
                $kurang_bayar = 0;
            } else {
                $jumlah_bayar = $request->jumlah_bayar;
                $kurang_bayar = $request->total_bayar - $request->jumlah_bayar;
            }

            $order = OrderPenjualan::create([
                'no_fraktur' => $noFraktur,
                'nama' => $request->nama,
                'no_telp' => $request->no_telp,
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'tanggal_pengiriman' => $request->tanggal_pengiriman,
                'jam_pengiriman' => $request->jam_pengiriman,
                'tanggal_pembuatan' => $request->tanggal_pembuatan,
                'status_pembayaran' => $request->status_pembayaran,
                'keterangan' => $request->keterangan,
                'jumlah_bayar' => $jumlah_bayar,
                'total_bayar' => $request->total_bayar,
                'kurang_bayar' => $kurang_bayar,
                'metode_pembayaran' => $request->metode_pembayaran,
                'tanggal_dp' => $request->tanggal_dp,
                'keterangan_staf' => $request->keterangan_staf,
                'penginput' => $request->penginput,
                'lokasi' => $request->lokasi,
                'kode_distribusi' => $kodeDistribusi,
                'outlet_id' => $request->outlet_id,
            ]);

            foreach ($request->produkList as $produk) {
                OrderPenjualanDetail::create([
                    'order_penjualan_id' => $order->id,
                    'master_produk_id' => $produk['master_produk_id'],
                    'jumlah_beli' => $produk['jumlah_beli'],
                ]);
            }

            DB::commit();
            return redirect()->route('apps.orderpenjualan.index')
                ->with('success', 'Order berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            report($e);
            return back()->withErrors(['msg' => 'Gagal menyimpan data.'])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'produkList' => 'required|array|min:1',
            'produkList.*.master_produk_id' => 'required|exists:master_produk,id',
            'produkList.*.jumlah_beli' => 'required|integer|min:1',
            'tanggal_pengiriman' => 'nullable|date',
            'jam_pengiriman' => 'nullable|in:Pagi,Siang,Sore,Malam',
            'tanggal_pembuatan' => 'nullable|date',
            'outlet_id' => 'required|exists:master_outlet,id', // <-- tambahkan validasi outlet_id
        ]);

        DB::beginTransaction();

        try {
            $order = OrderPenjualan::findOrFail($id);

            $order->update([
                'nama' => $request->nama,
                'no_telp' => $request->no_telp,
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'status_pembayaran' => $request->status_pembayaran,
                'tanggal_pengiriman' => $request->tanggal_pengiriman,
                'jam_pengiriman' => $request->jam_pengiriman,
                'tanggal_pembuatan' => $request->tanggal_pembuatan,
                'keterangan' => $request->keterangan,
                'jumlah_bayar' => $request->jumlah_bayar,
                'total_bayar' => $request->total_bayar,
                'kurang_bayar' => $request->total_bayar - $request->jumlah_bayar,
                'metode_pembayaran' => $request->metode_pembayaran,
                'tanggal_dp' => $request->tanggal_dp,
                'keterangan_staf' => $request->keterangan_staf,
                'penginput' => $request->penginput,
                'lokasi' => $request->lokasi,
                'outlet_id' => $request->outlet_id, // <-- tambahkan ini!
            ]);

            OrderPenjualanDetail::where('order_penjualan_id', $order->id)->delete();

            foreach ($request->produkList as $produk) {
                OrderPenjualanDetail::create([
                    'order_penjualan_id' => $order->id,
                    'master_produk_id' => $produk['master_produk_id'],
                    'jumlah_beli' => $produk['jumlah_beli'],
                ]);
            }

            DB::commit();
            return redirect()->route('apps.orderpenjualan.index')
                ->with('success', 'Order berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            report($e);
            return back()->withErrors(['msg' => 'Gagal mengupdate data.'])->withInput();
        }
    }

    public function kasir()
{
    $produk = MasterProduk::all()->map(function ($prd) {
        $prd->stok = OrderPenjualanDetail::where('master_produk_id', $prd->id)->sum('jumlah_beli');
        return $prd;
    });

    return Inertia::render('Apps/orderpenjualan/Kasir', [
        'produk' => $produk,
    ]);
}


    public function tambahStok(Request $request)
{
    $request->validate([
        'master_produk_id' => 'required|exists:master_produk,id',
        'jumlah_beli' => 'required|integer|min:1',
    ]);

    $lastOrder = OrderPenjualan::orderBy('id', 'desc')->first();
    $nextId = $lastOrder ? $lastOrder->id + 1 : 1;
    $kodeDistribusi = 'PP-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

    $order = OrderPenjualan::create([
        'nama' => 'Stok Tambahan',
        'stok' => $request->jumlah_beli, // <--- tambahkan stok di order_penjualan
        'kode_distribusi' => $kodeDistribusi,
        'created_id' => Auth::id(),
    ]);

    OrderPenjualanDetail::create([
        'order_penjualan_id' => $order->id,
        'master_produk_id' => $request->master_produk_id,
        'jumlah_beli' => $request->jumlah_beli,
    ]);

    return back()->with('success', 'Stok berhasil ditambah.');
}

public function orderProduksiKasir(Request $request)
{
    $produk = \App\Models\MasterProduk::all();
    $outlets = auth()->user()->outlets()->get();
    $outlet_id = $request->outlet_id ?? $outlets->first()->id ?? null;
    return Inertia::render('Apps/orderpenjualan/OrderProduksiKasir', [
        'produk' => $produk,
        'outlet' => $outlets,
        'outlet_id' => $outlet_id,
    ]);
}

public function storeOrderProduksiKasir(Request $request)
{
    $request->validate([
        'outlet_id' => 'required|exists:master_outlet,id',
        'produkList' => 'required|array|min:1',
        'produkList.*.master_produk_id' => 'required|exists:master_produk,id',
        'produkList.*.jumlah_beli' => 'required|integer|min:1',
        'tanggal_pengiriman' => 'required|date',
        'jam_pengiriman' => 'required|in:Pagi,Siang,Sore,Malam',
        'tanggal_pembuatan' => 'required|date',
    ]);

    $kodeDistribusi = 'PO-' . now()->format('YmdHis');
    $outlet = \App\Models\MasterOutlet::find($request->outlet_id);

    $total_bayar = 0;
    foreach ($request->produkList as $produk) {
        $prod = \App\Models\MasterProduk::find($produk['master_produk_id']);
        $total_bayar += ($prod->outlet_price ?? 0) * $produk['jumlah_beli'];
    }

    $last = \App\Models\OrderPenjualan::orderBy('id', 'desc')->first();
    $noFraktur = 'OP' . str_pad(($last ? $last->id + 1 : 1), 6, '0', STR_PAD_LEFT);

    $order = \App\Models\OrderPenjualan::create([
        'outlet_id' => $request->outlet_id, // <-- PENTING: simpan outlet_id!
        'nama' => $outlet->lokasi,
        'kode_distribusi' => $kodeDistribusi,
        'created_id' => \Auth::id(),
        'tanggal_pengiriman' => $request->tanggal_pengiriman,
        'jam_pengiriman' => $request->jam_pengiriman,
        'tanggal_pembuatan' => $request->tanggal_pembuatan,
        'status_pembayaran' => 2, // Lunas
        'jumlah_bayar' => $total_bayar,
        'total_bayar' => $total_bayar,
        'kurang_bayar' => 0,
        'no_fraktur' => $noFraktur,
        'lokasi' => $outlet->lokasi,
    ]);

    foreach ($request->produkList as $produk) {
        \App\Models\OrderPenjualanDetail::create([
            'order_penjualan_id' => $order->id,
            'master_produk_id' => $produk['master_produk_id'],
            'jumlah_beli' => $produk['jumlah_beli'],
        ]);
    }

    \App\Models\MonitoringOrder::create([
        'order_penjualan_id' => $order->id,
        'status_produksi' => 1,
        'created_id' => \Auth::id(),
    ]);

    return redirect()->route('apps.orderproduksi.kasir')->with('success', 'Order produksi berhasil dibuat.');
}

public function cicilan(Request $request, $id)
{
    $order = OrderPenjualan::findOrFail($id);

    $jumlah_cicilan = (int)$request->jumlah_cicilan;
    $cicilan_ke = $order->cicilan()->count() + 1;

    if ($cicilan_ke > 3) {
        return back()->withErrors(['msg' => 'Cicilan DP sudah maksimal 3x.']);
    }

    $order->cicilan()->create([
        'nominal' => $jumlah_cicilan,
        'cicilan_ke' => $cicilan_ke,
        'tanggal' => now()->toDateString(),
    ]);

    // Hitung total cicilan
    $total_cicilan = $order->cicilan()->sum('nominal');
    $order->jumlah_bayar = $total_cicilan;
    $order->kurang_bayar = $order->total_bayar - $total_cicilan;

    // Jika kurang bayar sudah 0, ubah status pembayaran jadi Lunas (2)
    if ($order->kurang_bayar <= 0) {
        $order->status_pembayaran = 2; // Lunas
        $order->kurang_bayar = 0; // pastikan tidak minus
    }

    $order->save();

    return back()->with('success', 'Cicilan berhasil disimpan.');
}

public function destroy($id)
{
    $order = \App\Models\OrderPenjualan::findOrFail($id);
    $order->delete();

    return redirect()->back()->with('success', 'Order berhasil dihapus');
}
}
<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\DistribusiProduk;
use App\Models\InventoryStok;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KonfirmasiDistribusiKasirController extends Controller
{
    public function index()
    {
        $userOutletIds = auth()->user()->outlets->pluck('id')->toArray();

        $distribusi = DistribusiProduk::with(['orderPenjualan.details.master_produk'])
            ->whereHas('orderPenjualan', function ($q) use ($userOutletIds) {
                $q->whereIn('outlet_id', $userOutletIds)
                  ->where('kode_distribusi', 'like', 'PO-%');
            })
            ->where('status_distribusi', 1) // status "Dikirim"
            ->get();

        return Inertia::render('Apps/KonfirmasiDistribusiKasir/Index', [
            'distribusi' => $distribusi,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status_konfirmasi' => 'required|in:2,3', // 2=Diterima, 3=Ditolak
        ]);

        $distribusi = DistribusiProduk::with('orderPenjualan.details')->findOrFail($id);

        if ($request->status_konfirmasi == 2) {
            // Diterima: update status, tambah stok ke kasir
            $distribusi->status_distribusi = 2;
            $distribusi->save();

            $outlet_id = $distribusi->orderPenjualan->outlet_id; // Ambil outlet_id dari order penjualan

            foreach ($distribusi->orderPenjualan->details as $detail) {
                $stok = InventoryStok::firstOrCreate(
                    [
                        'master_produk_id' => $detail->master_produk_id,
                        'outlet_id' => $outlet_id // <-- simpan outlet_id
                    ],
                    ['stok' => 0]
                );
                $stok->stok += $detail->jumlah_beli;
                $stok->save();
            }
        } elseif ($request->status_konfirmasi == 3) {
            // Ditolak: update status, monitoring order jadi "Sedang Diproduksi"
            $distribusi->status_distribusi = 3;
            $distribusi->save();

            $monitoring = $distribusi->monitoringOrder;
            if ($monitoring) {
                $monitoring->status_produksi = 1; // Sedang Diproduksi
                $monitoring->save();
            }
        }

        return back()->with('success', 'Status distribusi berhasil dikonfirmasi.');
    }
}

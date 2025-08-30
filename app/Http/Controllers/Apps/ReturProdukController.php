<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReturProduk;
use App\Models\MasterProduk;
use App\Models\InventoryStok;
use Carbon\Carbon;
use Inertia\Inertia;

class ReturProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->toDateString());
        $outlet_id = $request->get('outlet_id') ?? auth()->user()->outlets()->first()->id ?? null;

        // Ambil semua outlet user untuk dropdown
        $outlets = auth()->user()->outlets()->get();

        $returs = ReturProduk::with('produk')
            ->where('tanggal', $tanggal)
            ->where('outlet_id', $outlet_id) // <-- filter outlet
            ->get();

        $total_jumlah = $returs->sum('jumlah');
        $total_nominal = $returs->sum('total');
        $produkList = \App\Models\MasterProduk::all();

        return Inertia::render('Apps/Kasir/Retur', [
            'data' => $returs,
            'total_jumlah' => $total_jumlah,
            'total_nominal' => $total_nominal,
            'tanggal' => $tanggal,
            'outlets' => $outlets,
            'outlet_id' => $outlet_id,
            'produkList' => $produkList,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:master_produk,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $produk = MasterProduk::findOrFail($request->produk_id);
        $harga = $produk->outlet_price ?? 0;
        $total = $harga * $request->jumlah;

        // Ambil outlet aktif user
        $outlet_id = auth()->user()->outlets()->first()->id ?? null;

        // Kurangi stok
        $stok = InventoryStok::firstOrCreate([
            'master_produk_id' => $produk->id,
            'outlet_id' => $outlet_id // <-- pastikan outlet_id benar!
        ], ['stok' => 0]);
        if ($stok->stok < $request->jumlah) {
            return back()->withErrors(['msg' => 'Stok tidak cukup untuk retur!']);
        }
        $stok->stok -= $request->jumlah;
        $stok->save();

        // Simpan retur
        ReturProduk::create([
            'tanggal' => now()->toDateString(),
            'produk_id' => $produk->id,
            'jumlah' => $request->jumlah,
            'harga' => $harga,
            'total' => $total,
            'outlet_id' => $outlet_id, // <-- simpan outlet_id!
        ]);

        return back()->with('success', 'Retur produk berhasil!');
    }

    /**
     * Store multiple newly created resources in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMulti(Request $request)
    {
        $items = $request->input('items', []);
        $outlet_id = auth()->user()->outlets()->first()->id ?? null; // <-- ambil outlet aktif user

        foreach ($items as $item) {
            if (!isset($item['produk_id']) || !isset($item['jumlah'])) continue;

            $produk = MasterProduk::find($item['produk_id']);
            if (!$produk) continue;

            $harga = $produk->outlet_price ?? 0;
            $total = $harga * $item['jumlah'];

            $stok = InventoryStok::firstOrCreate([
                'master_produk_id' => $produk->id,
                'outlet_id' => $outlet_id // <-- pastikan outlet_id benar!
            ], ['stok' => 0]);
            if ($stok->stok < $item['jumlah']) continue;

            $stok->stok -= $item['jumlah'];
            $stok->save();

            ReturProduk::create([
                'tanggal' => now()->toDateString(),
                'produk_id' => $produk->id,
                'jumlah' => $item['jumlah'],
                'harga' => $harga,
                'total' => $total,
                'outlet_id' => $outlet_id, // <-- simpan outlet_id!
            ]);
        }
        return back()->with('success', 'Retur produk berhasil!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // Cetak PDF, Export Excel, Print: gunakan logic seperti monitoring stok
    public function cetakPdf(Request $request) { /* ... */ }
    public function exportExcel(Request $request) { /* ... */ }
    public function print(Request $request) { /* ... */ }
}


<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\MasterProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MasterProdukController extends Controller
{
    public function index()
    {
        // Ambil outlet_id yang dimiliki user login
$userOutletIds = auth()->user()->outlets->pluck('id')->toArray();
        $search = request()->search;
        //get produk
        $produk = MasterProduk::with('tipe_outlet') // <-- tambahkan ini
            ->when($search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama_produk', 'like', '%' . $search . '%')
                      ->orWhere('kode', 'like', '%' . $search . '%');
                });
            })
            ->latest()->paginate(10)->onEachSide(1);

        $tipeOutlets = \App\Models\TipeOutlet::all();

        //return inertia
        return Inertia::render('Apps/produk/Index', [
            'produk' => $produk,
            'tipeOutlets' => $tipeOutlets
]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'kode'         => 'required',
            'nama_produk'  => 'required',
            'outlet_price' => 'nullable|numeric|min:0',
            'harga_modal'  => 'nullable|numeric|min:0',
            'status'       => 'required'
        ]);

        $data = [
            'kode'         => $request->kode,
            'nama_produk'  => $request->nama_produk,
            'status'       => $request->status,
            'outlet_price' => $request->outlet_price,
            'harga_modal'  => $request->harga_modal,
            'created_id'   => \Auth::user()->id,
        ];

        $produk = MasterProduk::create($data);

        // Simpan relasi tipe outlet
        if ($request->tipe_outlet_id) {
            $produk->tipe_outlet()->sync([$request->tipe_outlet_id]);
        }

        return redirect()->route('apps.produk.index');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'kode'         => 'required',
            'nama_produk'  => 'required',
            'outlet_price' => 'nullable|numeric|min:0',
            'harga_modal'  => 'nullable|numeric|min:0',
            'status'       => 'required',
        ]);

        $data_produk = [
            'kode'         => $request->kode,
            'nama_produk'  => $request->nama_produk,
            'status'       => $request->status,
            'outlet_price' => $request->outlet_price,
            'harga_modal'  => $request->harga_modal,
        ];

        $produk = MasterProduk::findOrFail($id);
        $produk->update($data_produk);

        // Update relasi tipe outlet
        if ($request->tipe_outlet_id) {
            $produk->tipe_outlet()->sync([$request->tipe_outlet_id]);
        }

        return redirect()->route('apps.produk.index');
    }
}


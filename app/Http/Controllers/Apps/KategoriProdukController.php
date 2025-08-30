<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class KategoriProdukController extends Controller
{
    public function index()
    {
        $search = request()->search;
        //get katproduk
        $katproduk = KategoriProduk::when($search, function($katproduk, $search) {
            $katproduk = $katproduk->where('kategori_produk', 'like', '%'. $search . '%');
        })->latest()->paginate(10)->onEachSide(1);

        //return Inertia
        return Inertia::render('Apps/katproduk/Index',[
            'katproduk' => $katproduk
        ]);
    }

    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'nama_katproduk'      => 'required',
            'status'            => 'required'
        ]);

        //create katproduk
        KategoriProduk::create([
            'nama_katproduk'      => $request->nama_katproduk,
            'status'            => $request->status,
            'created_id'        => Auth::id(),
        ]);

        //redirect
        return redirect()->route('apps.katproduk.index');
    }

    public function update(Request $request, $id)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'nama_katproduk'  => 'required',
            'status'        => 'required',
        ]);
        //update katproduk
        $data_katproduk = [
            'nama_katproduk'  => $request->nama_katproduk,
            'status'        => $request->status,
        ];
        $ubahData = KategoriProduk::findOrFail($id);
        $ubahData->update($data_katproduk);
        //redirect
        return redirect()->route('apps.katproduk.index');
    }

    public function create()
    {
        $kategoriList = KategoriProduk::all();
        return Inertia::render('Apps/Kasir/Index', [
            'kategoriList' => $kategoriList,
        ]);
    }
}


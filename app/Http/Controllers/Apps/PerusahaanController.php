<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\MasterPerusahaan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PerusahaanController extends Controller
{
    public function index()
    {
        $search = request()->search;
        //get perusahaan
        $perusahaan = MasterPerusahaan::when($search, function($perusahaan, $search) {
            $perusahaan = $perusahaan->where('nama_pt', 'like', '%'. $search . '%');
        })->latest()->paginate(10)->onEachSide(1);

        //return inertia
        return Inertia::render('Apps/Perusahaan/Index',[
            'perusahaan' => $perusahaan
        ]);
    }

    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'nama_pt'           => 'required',
            'status'            => 'required'
        ]);

        //create perusa$perusahaan asset
        MasterPerusahaan::create([
            'nama_pt'           => $request->nama_pt,
            'status'            => $request->status,
        ]);

        //redirect
        return redirect()->route('apps.perusahaan.index');
    }

    public function update(Request $request, $id)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'nama_pt'   => 'required',
            'status'    => 'required',
        ]);
        //update perusa$perusahaan asset
        $data_perusahaan =[
            'nama_pt'   => $request->nama_pt,
            'status'    => $request->status,
        ];
        $ubahData = MasterPerusahaan::findOrFail($id);
        $ubahData->update($data_perusahaan);
        //redirect
        return redirect()->route('apps.perusahaan.index');
    }
}

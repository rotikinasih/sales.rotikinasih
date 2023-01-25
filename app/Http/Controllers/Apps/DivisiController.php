<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\MasterDivisi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DivisiController extends Controller
{
    public function index()
    {
        $search = request()->search;
        //get divisi
        $divisi = MasterDivisi::when($search, function($divisi, $search) {
            $divisi = $divisi->where('nama_divisi', 'like', '%'. $search . '%');
        })->latest()->paginate(10)->onEachSide(1);

        //return inertia
        return Inertia::render('Apps/Divisi/Index',[
            'divisi' => $divisi
        ]);
    }

    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'nama_divisi'       => 'required',
            'status'            => 'required'
        ]);

        //create divisi
        Masterdivisi::create([
            'nama_divisi'       => $request->nama_divisi,
            'status'            => $request->status,
        ]);

        //redirect
        return redirect()->route('apps.divisi.index');
    }

    public function update(Request $request, $id)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'nama_divisi'   => 'required',
            'status'        => 'required',
        ]);
        //update divisi
        $data_divisi = [
            'nama_divisi'   => $request->nama_divisi,
            'status'        => $request->status,
        ];
        $ubahData = Masterdivisi::findOrFail($id);
        $ubahData->update($data_divisi);
        //redirect
        return redirect()->route('apps.divisi.index');
    }
}

<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\MasterPosisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PosisiController extends Controller
{
    public function index()
    {
        $search = request()->search;
        //get posisi
        $posisi = MasterPosisi::when($search, function($posisi, $search) {
            $posisi = $posisi->where('nama_posisi', 'like', '%'. $search . '%');
        })->latest()->paginate(10)->onEachSide(1);

        //return inertia
        return Inertia::render('Apps/Posisi/Index',[
            'posisi' => $posisi
        ]);
    }

    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'nama_posisi'       => 'required',
            'status'            => 'required'
        ]);

        //create posisi
        $data = [
            'nama_posisi'       => $request->nama_posisi,
            'status'            => $request->status,
            'created_id'        => Auth::user()->id,
        ];

        // dd($data);

        MasterPosisi::create($data);

        //redirect
        return redirect()->route('apps.posisi.index');
    }

    public function update(Request $request, $id)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'nama_posisi'   => 'required',
            'status'        => 'required',
        ]);
        //update posisi
        $data_posisi = [
            'nama_posisi'   => $request->nama_posisi,
            'status'        => $request->status,
        ];
        $ubahData = MasterPosisi::findOrFail($id);
        $ubahData->update($data_posisi);
        //redirect
        return redirect()->route('apps.posisi.index');
    }
}

<?php

namespace App\Http\Controllers\Apps;

use App\Models\Karyawan;
use App\Models\Pelatihan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PelatihanController extends Controller
{
    public function index(){
        $search = request()->serach;

        //ambil pelatihan
        $pelatihan = Pelatihan::with('karyawan')->whereHas('karyawan', function($q) use($search){
            $q->where('nama_lengkap', 'like', '%'. $search . '%');
            })->latest()->paginate(10)->onEachSide(1);

        //ambil karyawan
        $karyawan = Karyawan::where('status_karyawan', 0)->get();

        // dd($karyawan);

        //return inertia render
        return Inertia::render('Apps/Pelatihan/Index', [
            'pelatihan' => $pelatihan,
            'karyawan' => $karyawan,
        ]);
    }

    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'karyawan_id'             => 'required',
            'jenis_pelatihan'         => 'required',
            'tanggal_pelatihan'       => 'required',
        ]);

        //create phk
        $data=[
            'karyawan_id'             => $request->karyawan_id,
            'jenis_pelatihan'         => $request->jenis_pelatihan,
            'tanggal_pelatihan'       => $request->tanggal_pelatihan,
            'lama_pelatihan'          => $request->lama_pelatihan,
            'created_id'              => Auth::id(),
        ];

        Pelatihan::create($data);

        //redirect
        return redirect()->route('apps.pelatihan.index');
    }

    public function update(Request $request, $id)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'karyawan_id'             => 'required',
            'jenis_pelatihan'         => 'required',
            'tanggal_pelatihan'       => 'required',
        ]);
        //update divisi
        $data = [
            'karyawan_id'             => $request->karyawan_id,
            'jenis_pelatihan'         => $request->jenis_pelatihan,
            'tanggal_pelatihan'       => $request->tanggal_pelatihan,
            'lama_pelatihan'          => $request->lama_pelatihan,
        ];
        $ubahData = Pelatihan::findOrFail($id);
        $ubahData->update($data);
        //redirect
        return redirect()->route('apps.pelatihan.index');
    }
}

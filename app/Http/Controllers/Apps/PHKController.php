<?php

namespace App\Http\Controllers\Apps;

use App\Models\KaryawanPHK;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Karyawan;

class PHKController extends Controller
{
    public function index()
    {
        $search = request()->search;
        //get perusahaan
        $karyawan_phk = KaryawanPHK::with('karyawan')->whereHas('karyawan', function ($query) use ($search) {
            $query->where('nama_lengkap', 'like', '%'. $search . '%');
        })->latest()->paginate(10)->onEachSide(1);

        $karyawan = Karyawan::all();

        //return inertia
        return Inertia::render('Apps/PHK/Index',[
            'karyawan_phk' => $karyawan_phk,
            'karyawan' => $karyawan,
        ]);
    }

    public function getNamaKaryawan($id){
        $karyawan = Karyawan::find($id);
        return $karyawan->name;
    }

    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'karyawan_id'             => 'required',
            'penyebab_phk'            => 'required',
            'tanggal_phk'             => 'required'
        ]);

        //create phk
        KaryawanPHK::create([
            'karyawan_id'             => $request->karyawan_id,
            'penyebab_phk'            => $request->penyebab_phk,
            'tanggal_phk'             => $request->tanggal_phk,
        ]);

        //redirect
        return redirect()->route('apps.phk.index');
    }

    public function update(Request $request, $id)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'karyawan_id'             => 'required',
            'penyebab_phk'            => 'required',
            'tanggal_phk'             => 'required'
        ]);
        //update divisi
        $data_phk = [
            'karyawan_id'             => $request->karyawan_id,
            'penyebab_phk'            => $request->penyebab_phk,
            'tanggal_phk'             => $request->tanggal_phk,
        ];
        $ubahData = KaryawanPHK::findOrFail($id);
        $ubahData->update($data_phk);
        //redirect
        return redirect()->route('apps.phk.index');
    }
}
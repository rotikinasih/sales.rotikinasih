<?php

namespace App\Http\Controllers\Apps;

use App\Models\KaryawanResign;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Karyawan;

class ResignController extends Controller
{
    public function index()
    {
        $search = request()->search;
        //get perusahaan
        $karyawan_resign = KaryawanResign::with('karyawan')->whereHas('karyawan', function ($query) use ($search) {
            $query->where('nama_lengkap', 'like', '%'. $search . '%');
        })->latest()->paginate(10)->onEachSide(1);

        $karyawan = Karyawan::where('status_karyawan', 0)->get();

        //return inertia
        return Inertia::render('Apps/Resign/Index',[
            'karyawan_resign' => $karyawan_resign,
            'karyawan' => $karyawan
        ]);
    }

    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'karyawan_id'                => 'required',
            'penyebab_resign'            => 'required',
            'tanggal_resign'             => 'required'
        ]);

        //create phk
        $data=[
            'karyawan_id'             => $request->karyawan_id,
            'penyebab_resign'            => $request->penyebab_resign,
            'tanggal_resign'             => $request->tanggal_resign,
        ];

        if(KaryawanResign::create($data)){
            //jika data resign berhasil dibuat, update status karyawan menjadi 2 = resign
            $karyawan = Karyawan::findOrfail($request->karyawan_id);
            $karyawan->status_karyawan = 2;
            $karyawan->save();
        }
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
            'penyebab_resign'            => 'required',
            'tanggal_resign'             => 'required'
        ]);
        //update divisi
        $data_resign = [
            'karyawan_id'             => $request->karyawan_id,
            'penyebab_resign'            => $request->penyebab_resign,
            'tanggal_resign'             => $request->tanggal_resign,
        ];
        $ubahData = KaryawanResign::findOrFail($id);
        $ubahData->update($data_resign);
        //redirect
        return redirect()->route('apps.phk.index');
    }
}
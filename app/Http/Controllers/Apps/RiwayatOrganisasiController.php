<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\MasterDivisi;
use App\Models\RiwayatOrganisasi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RiwayatOrganisasiController extends Controller
{
    public function index ($id){
        $search = request()->search;
        //get list
        $lists = RiwayatOrganisasi::with('karyawan', 'divisi')->whereHas('divisi', function($q) use($search){
            $q->where('nama_divisi', 'like', '%'. $search . '%');
            })->where('karyawan_id', $id)->latest()->paginate(10)->onEachSide(1);

        $nama_karyawan = Karyawan::where('id', $id)->first()->nama_karyawan;
        //get data divisi
        $divisi = MasterDivisi::where('status', 1)->get();

        return Inertia::render('Apps/Karyawan/ListOrganisasi', [
            'id_karyawan'   => $id,
            'lists'         => $lists,
            'nama'          => $nama_karyawan,
            'divisi'        => $divisi
        ]);
    }

    public function indexAll (){
        $search = request()->search;
        //get list
        $lists = RiwayatOrganisasi::with('karyawan', 'divisi')->whereHas('divisi', function($q) use($search){
            $q->where('nama_divisi', 'like', '%'. $search . '%');
            })->latest()->paginate(10)->onEachSide(1);

        // $nama_karyawan = Karyawan::where('id', $id)->first()->nama_karyawan;
        //get data divisi
        $divisi = MasterDivisi::where('status', 1)->get();

        return Inertia::render('Apps/Organisasi/Index', [
            // 'id_karyawan'   => $id,
            'lists'         => $lists,
            // 'nama'          => $nama_karyawan,
            'divisi'        => $divisi
        ]);
    }

    //untuk memperbaruhi data
    public function update(Request $request, $id)
    {
        /**
         * validate
         */
        // dd((int)$id);
        $this->validate($request, [
            'karyawan_id'       => 'required',
            'divisi_id'         => 'required',
            'tgl_gabung_grup'   => 'required',
            'tgl_masuk'         => 'required',
            'tgl_berakhir'      => 'required'
        ]);
        //update riwayat organisasi
        $data_karir =[
            'karyawan_id'       => $request->karyawan_id,
            'divisi_id'         => $request->divisi_id,
            'tgl_gabung_grup'   => $request->tgl_gabung_grup,
            'tgl_masuk'         => $request->tgl_masuk,
            'tgl_berakhir'      => $request->tgl_berakhir
        ];
        $ubahData = RiwayatOrganisasi::findOrFail((int)$id);
        $ubahData->update($data_karir);
        //redirect
        return redirect()->route('apps.organisasi.index', ['id' => $request->karyawan_id]);
    }
}

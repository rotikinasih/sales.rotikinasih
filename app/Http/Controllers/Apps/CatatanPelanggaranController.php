<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\CatatanPelanggaran;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CatatanPelanggaranController extends Controller
{
    public function index ($id){
        $search = request()->search;
        //pengelompokan kategori
        // $ringan =
        //get list
        $lists = CatatanPelanggaran::with('karyawan')->whereHas('karyawan', function($q) use($search){
            $q->where('catatan', 'like', '%'. $search . '%');
            })->where('karyawan_id', $id)->latest()->paginate(10)->onEachSide(1);

        $nama_karyawan = Karyawan::where('id', $id)->first()->nama_karyawan;

        return Inertia::render('Apps/Karyawan/CatatanPelanggaran', [
            'id_karyawan'   => $id,
            'lists'         => $lists,
            'nama'          => $nama_karyawan,
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
            'tanggal'           => 'required',
            'catatan'           => 'required',
            'tingkatan'         => 'required',
        ]);
        //update pelanggaran
        $data_pelanggaran =[
            'karyawan_id'       => $request->karyawan_id,
            'tanggal'           => $request->tanggal,
            'catatan'           => $request->catatan,
            'tingkatan'         => $request->tingkatan,
        ];
        $ubahData = CatatanPelanggaran::findOrFail((int)$id);
        $ubahData->update($data_pelanggaran);
        //redirect
        return redirect()->route('apps.pelanggaran.index', ['id' => $request->karyawan_id]);
    }
}

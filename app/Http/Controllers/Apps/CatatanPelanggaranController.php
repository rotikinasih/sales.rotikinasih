<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\CatatanPelanggaran;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Exports\PelanggaranExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class CatatanPelanggaranController extends Controller
{
    public function index ($id){
        $search = request()->search;
        //pengelompokan kategori
        
        //get list
        $lists = CatatanPelanggaran::with('karyawan')->whereHas('karyawan', function($q) use($search){
            $q->where('catatan', 'like', '%'. $search . '%');
            })->where('karyawan_id', $id)->latest()->paginate(10)->onEachSide(1);

        $nama_karyawan = Karyawan::where('id', $id)->first()->nama_lengkap;

        return Inertia::render('Apps/Karyawan/CatatanPelanggaran', [
            'id_karyawan'   => $id,
            'lists'         => $lists,
            'nama'          => $nama_karyawan,
        ]);
    }

    public function indexAll (){
        $search = request()->search;
        //pengelompokan kategori
        //get list
        $lists = CatatanPelanggaran::with('karyawan')->whereHas('karyawan', function($q) use($search){
            $q->where('catatan', 'like', '%'. $search . '%');
            })->orderBy('tanggal', 'DESC')->latest()->paginate(10)->onEachSide(1);

            // dd($lists);
        //get karyawan
        $karyawan= Karyawan::where('status_karyawan', 0)->get();

        return Inertia::render('Apps/Pelanggaran/Index', [
            // 'id_karyawan'   => $id,
            'lists'         => $lists,
            'karyawan'          => $karyawan,
        ]);
    }

    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'karyawan_id'       => 'required',
            'tanggal'           => 'required',
            'catatan'           => 'required',
            'tingkatan'         => 'required',
            'status'            => 'required',
            
        ]);

        //create riwayat organisasi
        $data = [
            'karyawan_id'       => $request->karyawan_id,
            'tanggal'           => $request->tanggal,
            'catatan'           => $request->catatan,
            'tingkatan'         => $request->tingkatan,
            'status'            => $request->status,
            'created_id'        => Auth::id(),
        ];

        // dd($data);

        CatatanPelanggaran::create($data);

        //redirect
        return redirect()->route('apps.pelanggaran.indexAll');
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
            'status'            => 'required',
        ]);
        //update pelanggaran
        $data_pelanggaran =[
            'karyawan_id'       => $request->karyawan_id,
            'tanggal'           => $request->tanggal,
            'catatan'           => $request->catatan,
            'tingkatan'         => $request->tingkatan,
            'status'            => $request->status,
        ];

        
        $ubahData = CatatanPelanggaran::findOrFail((int)$id);
        // dd($ubahData);
        $ubahData->update($data_pelanggaran);
        //redirect
        return redirect()->route('apps.pelanggaran.indexAll');
    }

    public function export(){
        $tanggal = date("d");
        $bulan = date("M");
        $tahun = date("Y");
        $jam = date("H:i:s");
        $response = Excel::download(new PelanggaranExport, 'Daftar Pelanggaran '.$tanggal." ".$bulan." ".Str::upper($tahun)." ".Str::upper($jam)." WIB".'.xlsx');
        ob_end_clean();
        return $response;
    }
}

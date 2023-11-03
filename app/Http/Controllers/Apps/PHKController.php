<?php

namespace App\Http\Controllers\Apps;

use App\Models\KaryawanPHK;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Auth;
use App\Exports\PHK_Export;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class PHKController extends Controller
{
    public function index()
    {
        $search = request()->search;
        //get perusahaan
        $karyawan_phk = KaryawanPHK::select('karyawan_phk.*', 'karyawan.nama_lengkap', 'karyawan.nik_karyawan')
            ->join('karyawan', 'karyawan_phk.karyawan_id', '=', 'karyawan.id')
            ->orderBy('karyawan.nik_karyawan', 'asc');

        if ($search == null) {
            $result = $karyawan_phk->paginate(10)->onEachSide(1);
        }else{
            $karyawan_phk->where('karyawan.nama_lengkap', 'like', '%' . $search . '%');
            $result = $karyawan_phk->paginate(10)->onEachSide(1);
        }

        $karyawan = Karyawan::where('status_karyawan', 0)->get();
        $karyawan_edit = Karyawan::all();

        //return inertia
        return Inertia::render('Apps/PHK/Index',[
            'karyawan_phk' => $result,
            'karyawan' => $karyawan,
            'karyawan_edit' => $karyawan_edit,
        ]);
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
        $data=[
            'karyawan_id'             => $request->karyawan_id,
            'penyebab_phk'            => $request->penyebab_phk,
            'tanggal_phk'             => $request->tanggal_phk,
            'created_id'              => Auth::id(),
        ];

        if(KaryawanPHK::create($data)){
            //jika data phk berhasil dibuat, update status karyawan menjadi 2 = phk
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

    public function export(){
        $tanggal = date("d");
        $bulan = date("M");
        $tahun = date("Y");
        $jam = date("H:i:s");
        $response = Excel::download(new PHK_Export, 'Daftar PHK '.$tanggal." ".$bulan." ".Str::upper($tahun)." ".Str::upper($jam)." WIB".'.xlsx');
        ob_end_clean();
        return $response;
    }
}
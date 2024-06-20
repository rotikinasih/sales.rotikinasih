<?php

namespace App\Http\Controllers\Apps;

use App\Exports\ResignExport;
use App\Models\KaryawanResign;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;


class ResignController extends Controller
{
    public function index()
    {
        $search = request()->search;

        $karyawan_resign = KaryawanResign::select('karyawan_resign.*', 'karyawan.nama_lengkap', 'karyawan.nik_karyawan')
            ->join('karyawan', 'karyawan_resign.karyawan_id', '=', 'karyawan.id')
            ->orderBy('karyawan.nik_karyawan', 'asc');

        if ($search == null) {
            $result = $karyawan_resign->paginate(10)->onEachSide(1);
        }else{
            $karyawan_resign->where('karyawan.nama_lengkap', 'like', '%' . $search . '%');
            $result = $karyawan_resign->paginate(10)->onEachSide(1);
        }

        // dd($result);



        $karyawan = Karyawan::where('status_karyawan', 0)->get();
        $karyawan_edit = Karyawan::all();

        //return inertia
        return Inertia::render('Apps/Resign/Index',[
            'karyawan_resign' => $result,
            'karyawan' => $karyawan,
            'karyawan_edit' => $karyawan_edit
        ]);
    }

    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'karyawan_id'                => 'required',
            'alasan_resign'              => 'required',
            'tanggal_resign'             => 'required'
        ]);

        //create phk
        $data=[
            'karyawan_id'                => $request->karyawan_id,
            'alasan_resign'              => $request->alasan_resign,
            'tanggal_resign'             => $request->tanggal_resign,
            'created_id'                 => Auth::id(),
        ];

        if(KaryawanResign::create($data)){
            //jika data resign berhasil dibuat, update status karyawan menjadi 1 = resign
            $karyawan = Karyawan::findOrfail($request->karyawan_id);
            $karyawan->status_karyawan = 1;
            $karyawan->save();
        }
        //redirect
        return redirect()->route('apps.resign.index');
    }

    public function update(Request $request, $id)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'karyawan_id'                => 'required',
            'alasan_resign'            => 'required',
            'tanggal_resign'             => 'required'
        ]);
        //update divisi
        $data_resign = [
            'karyawan_id'                => $request->karyawan_id,
            'alasan_resign'            => $request->alasan_resign,
            'tanggal_resign'             => $request->tanggal_resign,
        ];

        $ubahData = KaryawanResign::findOrFail($id);
        $ubahData->update($data_resign);
        //redirect
        return redirect()->route('apps.resign.index');
    }

    public function export(){
        $tanggal = date("d");
        $bulan = date("M");
        $tahun = date("Y");
        $jam = date("H:i:s");
        $response = Excel::download(new ResignExport, 'Daftar Resign '.$tanggal." ".$bulan." ".Str::upper($tahun)." ".Str::upper($jam)." WIB".'.xlsx');
        ob_end_clean();
        return $response;
    }

    public function delete($id)
    {
        $data = KaryawanResign::findOrFail($id);
        $data->deleted_status = 1;
        if ($data->save()) {
            $msg = 'Hapus Data Berhasil';
        } else {
            $msg = 'Hapus Data Gagal';
        }

        return redirect()->back()->with('msg', $msg);
    }
}

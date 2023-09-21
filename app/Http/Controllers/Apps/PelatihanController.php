<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Pelatihan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Exports\PelatihanExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;


class PelatihanController extends Controller
{
    public function index(){
        $search = request()->serach;

        //ambil pelatihan
        $pelatihan = Pelatihan::with('karyawan')
        ->whereHas('karyawan', function ($q) use ($search) {
            $q->where('nama_lengkap', 'like', '%' . $search . '%');
        })
        ->latest()
        ->paginate(10)
        ->onEachSide(1);

        //ambil karyawan
        $karyawan = Karyawan::where('status_karyawan', 0)->get();

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
            'kategori_pelatihan'      => 'required',
            'nama_pelatihan'          => 'required',
            'tanggal_pelatihan'       => 'required',
        ]);

        //create phk
        $data=[
            'karyawan_id'             => $request->karyawan_id,
            'kategori_pelatihan'      => $request->kategori_pelatihan,
            'nama_pelatihan'          => $request->nama_pelatihan,
            'tanggal_pelatihan'       => $request->tanggal_pelatihan,
            'durasi_pelatihan'        => $request->durasi_pelatihan,
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
            'kategori_pelatihan'      => 'required',
            'nama_pelatihan'          => 'required',
            'tanggal_pelatihan'       => 'required',
        ]);
        //update divisi
        $data = [
            'karyawan_id'             => $request->karyawan_id,
            'kategori_pelatihan'      => $request->kategori_pelatihan,
            'nama_pelatihan'          => $request->nama_pelatihan,
            'tanggal_pelatihan'       => $request->tanggal_pelatihan,
            'durasi_pelatihan'        => $request->durasi_pelatihan,
        ];
        $ubahData = Pelatihan::findOrFail($id);
        $ubahData->update($data);
        //redirect
        return redirect()->route('apps.pelatihan.index');
    }

    public function export(){
        $tanggal = date("d");
        $bulan = date("M");
        $tahun = date("Y");
        $jam = date("H:i:s");
        $response = Excel::download(new PelatihanExport, 'Daftar Pelatihan '.$tanggal." ".$bulan." ".Str::upper($tahun)." ".Str::upper($jam)." WIB".'.xlsx');
        ob_end_clean();
        return $response;
    }
}

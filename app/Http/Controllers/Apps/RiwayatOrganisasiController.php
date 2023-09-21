<?php

namespace App\Http\Controllers\Apps;

use App\Exports\OrganisasiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\MasterDivisi;
use App\Models\MasterJabatan;
use App\Models\MasterPerusahaan;
use App\Models\MasterPosisi;
use App\Models\RiwayatOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class RiwayatOrganisasiController extends Controller
{
    public function index ($id){
        $search = request()->search;
        //get list
        $lists = RiwayatOrganisasi::with('perusahaan', 'jabatan','karyawan', 'divisi', 'posisi')->whereHas('divisi', function($q) use($search){
            $q->where('nama_divisi', 'like', '%'. $search . '%');
            })->where('karyawan_id', $id)->latest()->paginate(10)->onEachSide(1);

        $nama_karyawan = Karyawan::where('id', $id)->first()->nama_lengkap;
        //get data divisi
        $perusahaan = MasterPerusahaan::where('status', 1)->get();
        $divisi = MasterDivisi::where('status', 1)->get();
        $jabatan = MasterJabatan::where('status', 1)->get();
        $posisi = MasterPosisi::where('status', 1)->get();

        return Inertia::render('Apps/Karyawan/ListOrganisasi', [
            'id_karyawan'   => $id,
            'lists'         => $lists,
            'nama'          => $nama_karyawan,
            'pt'            => $perusahaan,
            'divisi'        => $divisi,
            'jabatan'       => $jabatan,
            'posisi'        => $posisi,
        ]);
    }

    public function indexAll (){
        $search = request()->search;
        //get list
        $lists = RiwayatOrganisasi::with('perusahaan', 'jabatan','karyawan', 'divisi', 'posisi')->whereHas('karyawan', function($q) use($search){
            $q->where('nama_lengkap', 'like', '%'. $search . '%');
            })
            ->orderBy('tgl_masuk', 'DESC')->latest()->paginate(10)->onEachSide(1);

        //get karyawan
        $karyawan= Karyawan::where('status_karyawan', 0)->get();
        //get data entitas
        $perusahaan = MasterPerusahaan::where('status', 1)->get();
        //get data divisi
        $divisi = MasterDivisi::where('status', 1)->get();
        //get data jabatan
        $jabatan = MasterJabatan::where('status', 1)->get();
        //get data posisi
        $posisi = MasterPosisi::where('status', 1)->get();

        return Inertia::render('Apps/Organisasi/Index', [
            // 'id_karyawan'   => $id,
            'lists'         => $lists,
            'karyawan'      => $karyawan,
            'perusahaan'    => $perusahaan,
            'divisi'        => $divisi,
            'jabatan'       => $jabatan,
            'posisi'        => $posisi,
        ]);
    }

    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'karyawan_id'           => 'required',
            'kategori_karir'        => 'required',
            'pt_id'                 => 'required',
            'divisi_id'             => 'required',
            'jabatan_id'            => 'required',
            'posisi_id'             => 'required',
            'tgl_masuk'             => 'required',
            
        ]);

        //create riwayat organisasi
        $data = [
            'karyawan_id'           => $request->karyawan_id,
            'kategori_karir'        => $request->kategori_karir,
            'pt_id'                 => $request->pt_id,
            'divisi_id'             => $request->divisi_id,
            'jabatan_id'            => $request->jabatan_id,
            'posisi_id'             => $request->posisi_id,
            'tgl_masuk'             => $request->tgl_masuk,
            'tgl_berakhir'          => $request->tgl_berakhir,
            'created_id'            => Auth::id(),
        ];

        // dd($data);

        RiwayatOrganisasi::create($data);

        //redirect
        return redirect()->route('apps.organisasi.indexAll');
    }

    //untuk memperbaruhi data
    public function update(Request $request, $id)
    {
        /**
         * validate
         */
        // dd((int)$id);
        $this->validate($request, [
            'karyawan_id'           => 'required',
            'kategori_karir'        => 'required',
            'pt_id'                 => 'required',
            'divisi_id'             => 'required',
            'jabatan_id'            => 'required',
            'posisi_id'             => 'required',
            'tgl_masuk'             => 'required',
        ]);
        //update riwayat organisasi
        $data_karir =[
            'karyawan_id'           => $request->karyawan_id,
            'kategori_karir'        => $request->kategori_karir,
            'pt_id'                 => $request->pt_id,
            'divisi_id'             => $request->divisi_id,
            'jabatan_id'            => $request->jabatan_id,
            'posisi_id'             => $request->posisi_id,
            'tgl_masuk'             => $request->tgl_masuk,
            'tgl_berakhir'          => $request->tgl_berakhir,
        ];
        $ubahData = RiwayatOrganisasi::findOrFail((int)$id);
        $ubahData->update($data_karir);
        //redirect
        // return redirect()->route('apps.organisasi.index', ['id' => $request->karyawan_id]);
        return redirect()->route('apps.organisasi.indexAll');
    }

    public function export(){
        $tanggal = date("d");
        $bulan = date("M");
        $tahun = date("Y");
        $jam = date("H:i:s");
        $response = Excel::download(new OrganisasiExport, 'Daftar Karir Karyawan '.$tanggal." ".$bulan." ".Str::upper($tahun)." ".Str::upper($jam)." WIB".'.xlsx');
        ob_end_clean();
        return $response;
    }
}

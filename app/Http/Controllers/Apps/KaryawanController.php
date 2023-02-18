<?php

namespace App\Http\Controllers\Apps;

use App\Exports\KaryawanExport;
use App\Http\Controllers\Controller;
use App\Imports\KaryawanImport;
use App\Models\CatatanPelanggaran;
use App\Models\Karyawan;
use App\Models\MasterDivisi;
use App\Models\MasterJabatan;
use App\Models\MasterPerusahaan;
use App\Models\RiwayatOrganisasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request()->search;
        //get karyawan
        $karyawan = Karyawan::with('perusahaan', 'divisi', 'jabatan')->when($search, function($karyawan, $search) {
            $karyawan = $karyawan->where('nama_karyawan', 'like', '%'. $search . '%');
        })->latest()->paginate(10)->onEachSide(1);

        //get data divisi
        $divisi = MasterDivisi::where('status', 1)->get();
        //get data PT
        $perusahaan = MasterPerusahaan::where('status', 1)->get();
        //get data master jabatan
        $jabatan = MasterJabatan::where('status', 1)->get();

        //menghitung umur
        if($karyawan->isNotEmpty()){
            $now = Carbon::now()->isoFormat('Y-MM-D');
            $tanggal_lahir = Karyawan::get()->first()->tanggal_lahir;
            $age = Carbon::parse($tanggal_lahir)->diffInYears($now);
            DB::table('karyawan')->update(['umur' => $age]);
        }

        //return inertia
        return Inertia::render('Apps/Karyawan/Index', [
            'karyawan'  => $karyawan,
            'divisi'    => $divisi,
            'pt'        => $perusahaan,
            'jabatan'   => $jabatan
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //get PT
        $perusahaan = MasterPerusahaan::where('status', 1)->get();
        //get divisi
        $divisi = MasterDivisi::where('status', 1)->get();
        //get data master jabatan
        $jabatan = MasterJabatan::where('status', 1)->get();

        return Inertia::render('Apps/Karyawan/Create', [
            'perusahaan'        => $perusahaan,
            'divisi'            => $divisi,
            'jabatan'           => $jabatan
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'nama_karyawan'                 => 'required',
            'nik_karyawan'                  => 'required',
            'status_kerja'                  => 'required',
            'divisi_id'                     => 'required',
            'pt_id'                         => 'required',
            'tanggal_masuk'                 => 'required',
            'no_kk'                         => 'required',
            'nik_penduduk'                  => 'required',
            'grade'                         => 'required',
            'jabatan_id'                    => 'required',
            'no_hp'                         => 'required',
            'no_wa'                         => 'required',
            'gol_darah'                     => 'required',
            'email'                         => 'required',
            'tempat_lahir'                  => 'required',
            'tanggal_lahir'                 => 'required',
            'alamat_ktp'                    => 'required',
            'alamat_domisili'               => 'required',
            'jenis_kelamin'                 => 'required',
            'status_pernikahan'             => 'required',
            'pendidikan'                    => 'required',
            'nama_sekolah'                  => 'required',
            'kab_penugasan'                 => 'required',
            'ukuran_baju'                   => 'required',
            'no_sdr'                        => 'required',
            'hubungan'                      => 'required',
        ]);

        //create karyawan
        $karyawan = Karyawan::create([
            'nama_karyawan'             => $request->nama_karyawan,
            'nik_karyawan'              => $request->nik_karyawan,
            'status_kerja'              => $request->status_kerja,
            'divisi_id'                 => $request->divisi_id,
            'pt_id'                     => $request->pt_id,
            'tanggal_masuk'             => $request->tanggal_masuk,
            'tanggal_kontrak'           => $request->tanggal_kontrak,
            'no_kk'                     => $request->no_kk,
            'nik_penduduk'              => $request->nik_penduduk,
            'grade'                     => $request->grade,
            'jabatan_id'                => $request->jabatan_id,
            'no_hp'                     => $request->no_hp,
            'no_wa'                     => $request->no_wa,
            'no_bpjs_kesehatan'         => $request->no_bpjs_kesehatan,
            'no_bpjs_ketenagakerjaan'   => $request->no_bpjs_ketenagakerjaan,
            'gol_darah'                 => $request->gol_darah,
            'email'                     => $request->email,
            'tempat_lahir'              => $request->tempat_lahir,
            'tanggal_lahir'             => $request->tanggal_lahir,
            'alamat_ktp'                => $request->alamat_ktp,
            'alamat_domisili'           => $request->alamat_domisili,
            'jenis_kelamin'             => $request->jenis_kelamin,
            'status_pernikahan'         => $request->status_pernikahan,
            'pendidikan'                => $request->pendidikan,
            'nama_sekolah'              => $request->nama_sekolah,
            'kab_penugasan'             => $request->kab_penugasan,
            'rekening'                  => $request->rekening,
            'ukuran_baju'               => $request->ukuran_baju,
            'no_sdr'                    => $request->no_sdr,
            'hubungan'                  => $request->hubungan
        ]);

        //update umur
        $now = Carbon::now()->isoFormat('Y-MM-D');
        $tanggal_lahir = $karyawan->tanggal_lahir;
        $age = Carbon::parse($tanggal_lahir)->diffInYears($now);
        if($request->task_file){
            $extension = substr($request->nama_file, -3);
            $nama_file = $karyawan->nik_karyawan.'.'.$extension;
            Storage::putFileAs('public', $request->task_file, $nama_file);
            //cek status kerja
            if($request->status_kerja == 0){
                $awal_kontrak = $karyawan->tanggal_kontrak;
                $akhir_kontrak = date('Y-m-d', strtotime('+1 year', strtotime( $awal_kontrak )));
                $karyawan->update([
                    'foto'  => $nama_file,
                    'umur'  => $age,
                    'akhir_kontrak' => $akhir_kontrak
                ]);
            }else{
                $karyawan->update([
                    'foto'  => $nama_file,
                    'umur'  => $age,
                    'akhir_kontrak' => null
                ]);
            }

        }else{
            $nama_file = null;
            $karyawan->update([
                'umur'  => $age
            ]);
        }

        return redirect()->route('apps.karyawan.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Karyawan $karyawan)
    {
        //get PT
        $perusahaan = MasterPerusahaan::where('status', 1)->get();
        //get divisi
        $divisi = MasterDivisi::where('status', 1)->get();
        //get data master jabatan
        $jabatan = MasterJabatan::where('status', 1)->get();

        return Inertia::render('Apps/Karyawan/Edit', [
            'karyawan'          => $karyawan,
            'perusahaan'        => $perusahaan,
            'divisi'            => $divisi,
            'jabatan'           => $jabatan
        ]);
    }

    /**
     *
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'nama_karyawan'                 => 'required',
            'nik_karyawan'                  => 'required',
            'status_kerja'                  => 'required',
            'divisi_id'                     => 'required',
            'pt_id'                         => 'required',
            'tanggal_masuk'                 => 'required',
            'no_kk'                         => 'required',
            'nik_penduduk'                  => 'required',
            'grade'                         => 'required',
            'jabatan_id'                    => 'required',
            'no_hp'                         => 'required',
            'no_wa'                         => 'required',
            'gol_darah'                     => 'required',
            'email'                         => 'required',
            'tempat_lahir'                  => 'required',
            'tanggal_lahir'                 => 'required',
            'alamat_ktp'                    => 'required',
            'alamat_domisili'               => 'required',
            'jenis_kelamin'                 => 'required',
            'status_pernikahan'             => 'required',
            'pendidikan'                    => 'required',
            'nama_sekolah'                  => 'required',
            'kab_penugasan'                 => 'required',
            'ukuran_baju'                   => 'required',
            'no_sdr'                        => 'required',
            'hubungan'                      => 'required',
        ]);
        //
        if($request->task_file){
            //mengambil extension file
            $extension = substr($request->nama_file, -3);
            $nama_file = $karyawan->nik_karyawan.'.'.$extension;
            Storage::putFileAs('public', $request->task_file, $nama_file);
            //delete gambar di storage
            $foto = $karyawan->foto;
            //hapus di storage link
            Storage::disk('public')->delete('storage/', $foto);
        }else{
            $nama_file = $karyawan->foto;
        }
        //update karyawan
        $karyawan->update([
            'nama_karyawan'             => $request->nama_karyawan,
            'nik_karyawan'              => $request->nik_karyawan,
            'status_kerja'              => $request->status_kerja,
            'divisi_id'                 => $request->divisi_id,
            'pt_id'                     => $request->pt_id,
            'foto'                      => $nama_file,
            'tanggal_masuk'             => $request->tanggal_masuk,
            'tanggal_kontrak'           => $request->tanggal_kontrak,
            'no_kk'                     => $request->no_kk,
            'nik_penduduk'              => $request->nik_penduduk,
            'grade'                     => $request->grade,
            'jabatan_id'                => $request->jabatan_id,
            'no_hp'                     => $request->no_hp,
            'no_wa'                     => $request->no_wa,
            'no_bpjs_kesehatan'         => $request->no_bpjs_kesehatan,
            'no_bpjs_ketenagakerjaan'   => $request->no_bpjs_ketenagakerjaan,
            'gol_darah'                 => $request->gol_darah,
            'email'                     => $request->email,
            'tempat_lahir'              => $request->tempat_lahir,
            'tanggal_lahir'             => $request->tanggal_lahir,
            'alamat_ktp'                => $request->alamat_ktp,
            'alamat_domisili'           => $request->alamat_domisili,
            'jenis_kelamin'             => $request->jenis_kelamin,
            'status_pernikahan'         => $request->status_pernikahan,
            'pendidikan'                => $request->pendidikan,
            'nama_sekolah'              => $request->nama_sekolah,
            'kab_penugasan'             => $request->kab_penugasan,
            'rekening'                  => $request->rekening,
            'ukuran_baju'               => $request->ukuran_baju,
            'no_sdr'                    => $request->no_sdr,
            'hubungan'                  => $request->hubungan
        ]);

        //update umur
        $now = Carbon::now()->isoFormat('Y-MM-D');
        $tanggal_lahir = $karyawan->tanggal_lahir;
        $age = Carbon::parse($tanggal_lahir)->diffInYears($now);
        if($request->status_kerja == 0){
            $awal_kontrak = $karyawan->tanggal_kontrak;
            $akhir_kontrak = date('Y-m-d', strtotime('+1 year', strtotime( $awal_kontrak )));
            $karyawan->update([
                'umur'  => $age,
                'akhir_kontrak' => $akhir_kontrak
            ]);
        }else{
            $karyawan->update([
                'umur'  => $age,
                'akhir_kontrak' => null
            ]);
        }

        //redirect
        return redirect()->route('apps.karyawan.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //find karyawan by ID
        $karyawan = Karyawan::findOrFail($id);
        //delete gambar di storage
        $gambar_qrcode = $karyawan->foto;
        //hapus di storage link
        Storage::disk('public')->delete('storage/', $gambar_qrcode);
        //delete karyawan
        $karyawan->delete();
        //redirect
        return redirect()->route('apps.karyawan.index');
    }

    public function storeKarir(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'karyawan_id'           => 'required',
            'divisi_id'             => 'required',
            'tgl_gabung_grup'       => 'required',
            'tgl_masuk'             => 'required',
        ]);

        //create riwayat organisasi
        RiwayatOrganisasi::create([
            'karyawan_id'       => $request->karyawan_id,
            'divisi_id'         => $request->divisi_id,
            'tgl_gabung_grup'   => $request->tgl_gabung_grup,
            'tgl_masuk'         => $request->tgl_masuk,
            'tgl_berakhir'      => $request->tgl_berakhir
        ]);

        //redirect
        return redirect()->route('apps.karyawan.index');
    }

    public function storePelanggaran(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'karyawan_id'           => 'required',
            'catatan'               => 'required',
            'tanggal'               => 'required',
            'tingkatan'             => 'required'
        ]);

        //create pelanggaran
        CatatanPelanggaran::create([
            'karyawan_id'       => $request->karyawan_id,
            'catatan'           => $request->catatan,
            'tanggal'           => $request->tanggal,
            'tingkatan'         => $request->tingkatan
        ]);

        //redirect
        return redirect()->route('apps.karyawan.index');
    }

    /**
     * export
     *
     * @param mixed
     * @return void
     */
    public function export(){
        $tanggal = date("d");
        $bulan = date("M");
        $tahun = date("Y");
        $jam = date("H:i:s");
        $response = Excel::download(new KaryawanExport, 'Karyawan '.$tanggal." ".$bulan." ".Str::upper($tahun)." ".Str::upper($jam)." WIB".'.xlsx');
        ob_end_clean();
        return $response;
    }

    public function import(Request $request)
	{
		// validasi
		$this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
		]);

		// menangkap file excel
		$file = $request->file('file');

		// membuat nama file unik
		$nama_file = rand().$file->getClientOriginalName();

		// upload ke folder file_karyawan di dalam folder public
		$file->move('file_karyawan',$nama_file);

		// import data
		Excel::import(new KaryawanImport, public_path('/file_karyawan/'.$nama_file));

		// notifikasi dengan session
		// Session::flash('sukses','Data Siswa Berhasil Diimport!');

		//redirect
        return redirect()->route('apps.karyawan.index');
	}
}

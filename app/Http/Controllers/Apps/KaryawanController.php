<?php

namespace App\Http\Controllers\Apps;

use App\Exports\KaryawanExport;
use App\Exports\KaryawanFormatExport;
use App\Http\Controllers\Controller;
use App\Imports\KaryawanImport;
use App\Models\CatatanPelanggaran;
use App\Models\Karyawan;
use App\Models\MasterDivisi;
use App\Models\MasterJabatan;
use App\Models\MasterPerusahaan;
use App\Models\RiwayatOrganisasi;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use PDF;

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
            $karyawan = $karyawan->where('nama_lengkap', 'like', '%'. $search . '%')
                                ->orWhere('nik_karyawan', 'like', '%'. $search . '%')
                                ->orWhere('nik_penduduk', 'like', '%'. $search . '%');
        })->latest()->paginate(10)->onEachSide(1);

        //get data divisi
        $divisi = MasterDivisi::where('status', 1)->get();
        //get data PT
        $perusahaan = MasterPerusahaan::where('status', 1)->get();
        //get data master jabatan
        $jabatan = MasterJabatan::where('status', 1)->get();
        //menghitung umur
        if($karyawan->isNotEmpty()){
            foreach ($karyawan as $k){
                
                $now = Carbon::now()->isoFormat('Y-MM-D');
                $tanggal_lahir = $k->tanggal_lahir;
                // cek tanggal lahir
                if($tanggal_lahir == null){
                    $tahun_lahir = 'null';
                    $age = 0;
                    //update umur karyawan
                    $k->update(['umur' => $age]);
                    // update komposisi generasi
                    $k->update(['komposisi_generasi' => null]);
                }else{
                    $tahun_lahir = date('Y', strtotime($k->tanggal_lahir));
                    $age = Carbon::parse($tanggal_lahir)->diffInYears($now);
                    //update umur karyawan
                    $k->update(['umur' => $age]);

                    // update komposisi generasi
                    //gen Boomers (1946-1964)
                    if($tahun_lahir >= 1946 && $tahun_lahir <= 1964){
                        $k->update(['komposisi_generasi' => 'Gen Boomers']);
                    }
                    //gen X (1965-1980)
                    if($tahun_lahir >= 1965 && $tahun_lahir <= 1980){
                        $k->update(['komposisi_generasi' => 'Gen X']);
                    }
                    //gen Y atau Milenial(1981-1996)
                    if($tahun_lahir >= 1981 && $tahun_lahir <= 1996){
                        $k->update(['komposisi_generasi' => 'Gen Milenial']);
                    }
                    //gen Z (1997-2012)
                    if($tahun_lahir >= 1997 && $tahun_lahir <= 2012){
                        $k->update(['komposisi_generasi' => 'Gen Z']);
                    }
                    //gen Alpha/Milenium (2013-2025)
                    if($tahun_lahir >= 2013 && $tahun_lahir <= 2025){
                        $k->update(['komposisi_generasi' => 'Gen Alpha']);
                    }
                }

                //status kerja kontrak
                $awal_kontrak = $k->tanggal_kontrak;
                $masa_kontrak = $k->masa_kontrak;
                $waktu_sekarang = date('Y-m-d');
                if($k->status_kerja == 1){
                    //tanggal akhir kontrak
                    if($k->tanggal_kontrak){
                        $akhir_kontrak = date('Y-m-d', strtotime( $awal_kontrak . "+$masa_kontrak month"));
                    }else{
                        $akhir_kontrak =  null;
                    }
                    $new_awal_kontrak = new DateTime($awal_kontrak);
                    //menghitung interval lama kontrak
                    $interval = $new_awal_kontrak->diff(new DateTime($akhir_kontrak));
                    //format interval lama kontrak
                    $masa_kerja_tahun = $interval->format('%y tahun, %m bulan, %d hari');

                    $k->update([
                        'akhir_kontrak' => $akhir_kontrak,
                        'masa_kerja_bulan' => $masa_kontrak,
                        'masa_kerja_tahun' => $masa_kerja_tahun,
                    ]);
                }
                
                //status kerja tetap
                if($k->status_kerja == 2){
                    // $masa_kontrak = $k->masa_kontrak;
                    // $awal_kontrak =$k->tanggal_kontrak;
                    $tanggal_karyawan_tetap =$k->tanggal_karyawan_tetap;
                     //tanggal akhir kontrak
                    $akhir_kontrak = date('Y-m-d', strtotime( $awal_kontrak . "+$masa_kontrak month"));
                    //menghitung interval lama karyawan tetap
                    $interval_pertama = date_diff(new DateTime($tanggal_karyawan_tetap), new DateTime($waktu_sekarang));
                    // dd($interval_pertama);
                    
                    $masa_kerja = date('Y-m-d', strtotime( $awal_kontrak . "+$masa_kontrak month" . "+$interval_pertama->days day"));
                    $new_tanggal_karyawan_tetap = new DateTime($awal_kontrak);
                    $interval_kedua = $new_tanggal_karyawan_tetap->diff(new DateTime($masa_kerja));
                    $all_time = $interval_kedua->format('%y tahun, %m bulan, %d hari');
                    $masa_kerja_bulan = ($interval_kedua->y * 12) + $interval_kedua->m;
                    // dd($new2);
                    $k->update([
                        'masa_kerja_tahun' => $all_time,
                        'masa_kerja_bulan' => $masa_kerja_bulan,
                        'akhir_kontrak' => $akhir_kontrak,
                    ]);
                }
            }
        }

        //return inertia
        return Inertia::render('Apps/Karyawan/Index', [
            'karyawan'  => $karyawan,
            'divisi'    => $divisi,
            'pt'        => $perusahaan,
            'jabatans'   => $jabatan
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
        $rules = [
            'nama_lengkap'                 => 'required',
        ];

        $messages = [
            'nama_lengkap.required' => 'Nama Lengkap harus diisi',
        ];

        $validatedData = $request->validate($rules, $messages);

        //create karyawan
        $karyawan = Karyawan::create([

            //data pribadi
            'nama_lengkap' => $request->nama_lengkap,
            'nama_panggilan' => $request->nama_panggilan,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'gol_darah' => $request->gol_darah,
            'riwayat_penyakit' => $request->riwayat_penyakit,
            'nik_penduduk' => $request->nik_penduduk,
            'no_kk' => $request->no_kk,
            'kode_pos' => $request->kode_pos,
            'alamat_ktp' => $request->alamat_ktp,
            'alamat_domisili' => $request->alamat_domisili,
            'pendidikan' => $request->pendidikan,
            'nama_sekolah' => $request->nama_sekolah,
            'jurusan' => $request->jurusan,
            'email_pribadi' => $request->email_pribadi,
            'no_telp' => $request->no_telp,
            'no_wa' => $request->no_wa,
            'no_keluarga' => $request->no_keluarga,
            'hubungan_keluarga' => $request->hubungan_keluarga,
            'status_pernikahan' => $request->status_pernikahan,
            'status_keluarga' => $request->status_keluarga,
            'jenis_sosmed' => $request->jenis_sosmed,
            'nama_sosmed' => $request->nama_sosmed,

            //data di perusahaan
            'nik_karyawan' => $request->nik_karyawan,
            'pt_id' => $request->pt_id,
            'divisi_id' => $request->divisi_id,
            'jabatan_id' => $request->jabatan_id,
            'grade' => $request->grade,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_karyawan_tetap' => $request->tanggal_karyawan_tetap,
            'tanggal_kontrak' => $request->tanggal_kontrak,
            'masa_kontrak' => $request->masa_kontrak,
            // 'masa_kerja' => $request->masa_kontrak,
            'status_kerja' => $request->status_kerja,
            'komposisi_peran' => $request->komposisi_peran,
            'kota_rekruitmen' => $request->kota_rekruitmen,
            'kota_penugasan' => $request->kota_penugasan,
            'pengalaman_kerja_terakhir' => $request->pengalaman_kerja_terakhir,
            'jabatan_kerja_terakhir' => $request->jabatan_kerja_terakhir,
            'nama_bank' => $request->nama_bank,
            'rekening' => $request->rekening,
            'no_npwp' => $request->no_npwp,
            'email_interanl' => $request->email_interanl,
            'no_bpjs_kesehatan' => $request->no_bpjs_kesehatan,
            'no_bpjs_ketenagakerjaan' => $request->no_bpjs_ketenagakerjaan,
            'ukuran_baju' => $request->ukuran_baju,
        ]);

        // dd($data);


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
                $masa_kontrak = $karyawan->masa_kontrak;
                $akhir_kontrak = date('Y-m-d', strtotime($masa_kontrak, 'month', strtotime( $awal_kontrak )));
                $karyawan->update([
                    'foto'  => $nama_file,
                    'umur'  => $age,
                    'akhir_kontrak' => $akhir_kontrak,
                    'masa_kerja' => $masa_kontrak,
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
        $rules = [
            'nama_lengkap'                 => 'required',
        ];

        $messages = [
            'nama_lengkap.required' => 'Nama Lengkap harus diisi',
        ];

        $validatedData = $request->validate($rules, $messages);
        
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
            'foto'           => $nama_file,
            'nama_lengkap' => $request->nama_lengkap,
            'nama_panggilan' => $request->nama_panggilan,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'gol_darah' => $request->gol_darah,
            'riwayat_penyakit' => $request->riwayat_penyakit,
            'nik_penduduk' => $request->nik_penduduk,
            'no_kk' => $request->no_kk,
            'kode_pos' => $request->kode_pos,
            'alamat_ktp' => $request->alamat_ktp,
            'alamat_domisili' => $request->alamat_domisili,
            'pendidikan' => $request->pendidikan,
            'nama_sekolah' => $request->nama_sekolah,
            'jurusan' => $request->jurusan,
            'email_pribadi' => $request->email_pribadi,
            'no_telp' => $request->no_telp,
            'no_wa' => $request->no_wa,
            'no_keluarga' => $request->no_keluarga,
            'hubungan_keluarga' => $request->hubungan_keluarga,
            'status_pernikahan' => $request->status_pernikahan,
            'status_keluarga' => $request->status_keluarga,
            'jenis_sosmed' => $request->jenis_sosmed,
            'nama_sosmed' => $request->nama_sosmed,

            //data di perusahaan
            'nik_karyawan' => $request->nik_karyawan,
            'pt_id' => $request->pt_id,
            'divisi_id' => $request->divisi_id,
            'jabatan_id' => $request->jabatan_id,
            'grade' => $request->grade,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_karyawan_tetap' => $request->tanggal_karyawan_tetap,
            'tanggal_kontrak' => $request->tanggal_kontrak,
            'masa_kontrak' => $request->masa_kontrak,
            'status_kerja' => $request->status_kerja,
            'komposisi_peran' => $request->komposisi_peran,
            'kota_rekruitmen' => $request->kota_rekruitmen,
            'kota_penugasan' => $request->kota_penugasan,
            'nama_bank' => $request->nama_bank,
            'rekening' => $request->rekening,
            'no_npwp' => $request->no_npwp,
            'email_internal' => $request->email_internal,
            'no_bpjs_kesehatan' => $request->no_bpjs_kesehatan,
            'no_bpjs_ketenagakerjaan' => $request->no_bpjs_ketenagakerjaan,
            'ukuran_baju' => $request->ukuran_baju,
            'pengalaman_kerja_terakhir' => $request->pengalaman_kerja_terakhir,
            'jabatan_kerja_terakhir' => $request->jabatan_kerja_terakhir,
        ]);

        // //update umur
        // $now = Carbon::now()->isoFormat('Y-MM-D');
        // $tanggal_lahir = $karyawan->tanggal_lahir;
        // $age = Carbon::parse($tanggal_lahir)->diffInYears($now);
        // if($request->status_kerja == 0){
        //     $awal_kontrak = $karyawan->tanggal_kontrak;
        //     // $akhir_kontrak = date('Y-m-d', strtotime('+1 year', strtotime( $awal_kontrak )));
        //     $karyawan->update([
        //         'umur'  => $age,
        //         // 'akhir_kontrak' => $akhir_kontrak
        //     ]);
        // }else{
        //     $karyawan->update([
        //         'umur'  => $age,
        //         'akhir_kontrak' => null
        //     ]);
        // }

        //redirect
        return redirect()->route('apps.karyawan.index');
    }

    // public function detail(Karyawan $karyawan)
    // {
    //     //get PT
    //     $perusahaan = MasterPerusahaan::where('status', 1)->get();
    //     //get divisi
    //     $divisi = MasterDivisi::where('status', 1)->get();
    //     //get data master jabatan
    //     $jabatan = MasterJabatan::where('status', 1)->get();

    //     return Inertia::render('Apps/Karyawan/Edit', [
    //         'karyawan'          => $karyawan,
    //         'perusahaan'        => $perusahaan,
    //         'divisi'            => $divisi,
    //         'jabatan'           => $jabatan
    //     ]);
    // }

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
            'pt_id'                 => 'required',
            'jabatan_id'            => 'required',
            // 'tgl_gabung_grup'       => 'required',
            'tgl_masuk'             => 'required',
        ]);

        //create riwayat organisasi
        $data = [
            'karyawan_id'       => $request->karyawan_id,
            'divisi_id'         => $request->divisi_id,
            'pt_id'             => $request->pt_id,
            'jabatan_id'        => $request->jabatan_id,
            // 'tgl_gabung_grup'   => $request->tgl_gabung_grup,
            'tgl_masuk'         => $request->tgl_masuk,
            'tgl_berakhir'      => $request->tgl_berakhir
        ];

        RiwayatOrganisasi::create($data);

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
            'tingkatan'             => 'required',
            'status'                => 'required',
        ]);

        //create pelanggaran
        CatatanPelanggaran::create([
            'karyawan_id'       => $request->karyawan_id,
            'catatan'           => $request->catatan,
            'tanggal'           => $request->tanggal,
            'tingkatan'         => $request->tingkatan,
            'status'            => $request->status,
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
        // try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');

            // dd($file);
            // membuat nama file unik
            $nama_file = rand().$file->getClientOriginalName();
            // upload ke folder file_karyawan di dalam folder public
            $file->move('file_karyawan',$nama_file);
            // import data
            Excel::import(new KaryawanImport, public_path('/file_karyawan/'.$nama_file));
            //redirect
            return redirect()->back()->with('success', 'Import Data Saved Successfully');
        // } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        //     $failures = $e->failures();
        //     foreach ($failures as $failure) {
        //         $failure->row(); // row that went wrong
        //         $failure->attribute(); // either heading key (if using heading row concern) or column index
        //         $failure->errors(); // Actual error messages from Laravel validator
        //         $failure->values(); // The values of the row that has failed.
        //      }
        //      return redirect()->back()->with('error', $failures);
        // }
	}

    public function format(){
        $tanggal = date("d");
        $bulan = date("M");
        $tahun = date("Y");
        $jam = date("H:i:s");
        $response = Excel::download(new KaryawanFormatExport, 'Format Karyawan '.$tanggal." ".$bulan." ".Str::upper($tahun)." ".Str::upper($jam)." WIB".'.xlsx');
        ob_end_clean();
        return $response;
    }

    public function downloadPDF($id){
        $data = [
            'detail_karyawan' => Karyawan::findOrFail($id)
        ]; // Any data you want to pass to the view
        $pdf = PDF::loadView('pdf.detail_karyawan', $data);

        // return $pdf->download('detail_karyawan.pdf');
        return Response::make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="detail_karyawan.pdf"'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\KaryawanPHK;
use App\Models\KaryawanResign;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {

        $karyawan = Karyawan::with('perusahaan', 'divisi', 'jabatan', 'posisi')
        ->where('status_karyawan', 0)->get();

        //menghitung umur dan masa kerja
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

                //status kerja training
                if($k->status_kerja == 3){
                    $k->update([
                        'akhir_kontrak' => null,
                        'masa_kontrak' => 0,
                        'masa_kerja_bulan' => 0,
                        'masa_kerja_tahun' => 0,
                    ]);
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
                    $interval = $new_awal_kontrak->diff(new DateTime($waktu_sekarang));

                    $years = $interval->y;
                    $months = $interval->m;

                    $masa_kerja_bulan = $years * 12 + $months;
                    //format interval lama kontrak
                    $masa_kerja_tahun = $interval->format('%y tahun, %m bulan, %d hari');

                    $k->update([
                        'akhir_kontrak' => $akhir_kontrak,
                        'masa_kerja_bulan' => $masa_kerja_bulan,
                        'masa_kerja_tahun' => $masa_kerja_tahun,
                    ]);
                }
                
                //status kerja tetap
                if($k->status_kerja == 2){
                    $awal_kontrak =$k->tanggal_kontrak;
                
                    $interval_pertama = date_diff(new DateTime($awal_kontrak), new DateTime($waktu_sekarang));

                    $all_time = $interval_pertama->format('%y tahun, %m bulan, %d hari');

                    $masa_kerja_bulan = ($interval_pertama->y * 12) + $interval_pertama->m;
                    // dd($new2);
                    $k->update([
                        'masa_kerja_tahun' => $all_time,
                        'masa_kerja_bulan' => $masa_kerja_bulan,
                        // 'akhir_kontrak' => $akhir_kontrak,
                    ]);
                }
            }
        }

        //menampilkan tanggal hari ini
        $day = date('d F Y');

        //jumlah karyawan berdasarkan PT
        $chart_pt = Karyawan::select('master_perusahaan.nama_pt AS title', DB::raw('COUNT(*) AS total'))
            ->join('master_perusahaan', 'master_perusahaan.id', '=', 'karyawan.pt_id')
            ->where('status_karyawan', 0)
            ->groupBy('title')
            ->get();

            if(count($chart_pt)) {
                foreach ($chart_pt as $data) {
                    if ($data->title !== null) {
                        $pt []= $data->title;
                        $total_pt []= $data->total;
                    }
                }
            }else{
                $pt[]   = "";
                $total_pt[]  = "";
            }

        //jumlah karyawan berdasarkan Divisi
        $chart_divisi = Karyawan::select('master_divisi.nama_divisi AS title', DB::raw('COUNT(*) AS total'))
        ->join('master_divisi', 'master_divisi.id', '=', 'karyawan.divisi_id')
        ->where('status_karyawan', 0)
        ->groupBy('title')
        ->get();

        if(count($chart_divisi)) {
            foreach ($chart_divisi as $data) {
                if ($data->title !== null) {
                    $divisi []= $data->title;
                    $total_divisi []= $data->total;
                }
            }
        }else{
            $divisi[]   = "";
            $total_divisi[]  = "";
        }

        //jumlah karyawan berdasarkan Jabatan
        $chart_jabatan = Karyawan::select('master_jabatan.nama_jabatan AS title', DB::raw('COUNT(*) AS total'))
        ->join('master_jabatan', 'master_jabatan.id', '=', 'karyawan.jabatan_id')
        ->where('status_karyawan', 0)
        ->groupBy('title')
        ->get();

        if(count($chart_jabatan)) {
            foreach ($chart_jabatan as $data) {
                if ($data->title !== null) {
                    $jabatan []= $data->title;
                    $total_jabatan []= $data->total;
                }
            }
        }else{
            $jabatan[]   = "";
            $total_jabatan[]  = "";
        }

        //jumlah karyawan berdasarkan Jabatan
        $chart_posisi = Karyawan::select('master_posisi.nama_posisi AS title', DB::raw('COUNT(*) AS total'))
        ->join('master_posisi', 'master_posisi.id', '=', 'karyawan.posisi_id')
        ->where('status_karyawan', 0)
        ->groupBy('title')
        ->get();

        if(count($chart_posisi)) {
            foreach ($chart_posisi as $data) {
                if ($data->title !== null) {
                    $posisi []= $data->title;
                    $total_posisi []= $data->total;
                }
            }
        }else{
            $posisi[]   = "";
            $total_posisi[]  = "";
        }

        //kota penugasan
        $chart_kota_penugasan = Karyawan::select('karyawan.kota_penugasan AS title', DB::raw('COUNT(*) AS total'))
        ->where('status_karyawan', 0)
        ->groupBy('title')
        ->orderBy('total', 'DESC')
        ->get();

        if(count($chart_kota_penugasan)) {
            foreach ($chart_kota_penugasan as $key => $data) {
                if ($data->title !== null) {
                    $kota_penugasan[] = $data->title;
                    $total_kota_penugasan[] = $data->total;
                }
            }
        }else{
            $kota_penugasan[] = "";
            $total_kota_penugasan[] = "";
        }

        // dd($total_kota_penugasan);

        $chart_komposisi_generasi = Karyawan::select('karyawan.komposisi_generasi AS title', DB::raw('COUNT(*) AS total'))
        ->where('status_karyawan', '=', 0)
        ->groupBy('komposisi_generasi')
        ->get();

        if(count($chart_komposisi_generasi)) {
            foreach ($chart_komposisi_generasi as $data) {
                if ($data->title !== null) {
                    $komposisi_generasi[] = $data->title;
                    $total_komposisi_generasi[] = $data->total;
                }
            }
        }else{
            $komposisi_generasi[] = "";
            $total_komposisi_generasi[] = "";
        }

        // dd( $total_komposisi_generasi);
        $chart_jenis_kelamin = Karyawan::selectRaw('CASE WHEN jenis_kelamin = 1 THEN "Laki-laki" ELSE "Perempuan" END AS title')
        ->selectRaw('COUNT(*) AS total')
        ->where('status_karyawan', 0)
        ->groupBy('title')
        ->get();

        if(count($chart_jenis_kelamin)) {
            foreach ($chart_jenis_kelamin as $data) {
                if ($data->title !== null) {
                    $jenis_kelamin[] = $data->title;
                    $total_jenis_kelamin[] = $data->total;
                }
            }
        }else{
            $jenis_kelamin[] = "";
            $total_jenis_kelamin[] = "";
        }

        $chart_status_kerja = Karyawan::selectRaw('CASE 
        WHEN status_kerja = 1 THEN "Kontrak" 
        WHEN status_kerja = 2 THEN "Tetap" 
        ELSE "Training" END
        AS title')
        ->selectRaw('COUNT(*) AS total')
        ->where('status_karyawan', 0)
        ->groupBy('title')
        ->get();

        if(count($chart_status_kerja)) {
            foreach ($chart_status_kerja as $data) {
                if ($data->title !== null) {
                    $status_kerja[] = $data->title;
                    $total_status_kerja[] = $data->total;
                }
            }
        }else{
            $status_kerja[] = "";
            $total_status_kerja[] = "";
        }


        $chart_komposisi_peran = Karyawan::selectRaw('CASE 
        WHEN komposisi_peran = 1 THEN "Support" 
        ELSE "Core" END 
        AS title')
        ->selectRaw('COUNT(*) AS total')
        ->where('status_karyawan', 0)
        ->groupBy('title')
        ->get();

        if(count($chart_komposisi_peran)) {
            foreach ($chart_komposisi_peran as $data) {
                if ($data->title !== null) {
                    $komposisi_peran[] = $data->title;
                    $total_komposisi_peran[] = $data->total;
                }
            }
        }else{
            $komposisi_peran[] = "";
            $total_komposisi_peran[] = "";
        }

        $chart_pendidikan = Karyawan::selectRaw('CASE 
        WHEN pendidikan=1 THEN "SD" 
        WHEN pendidikan=2 THEN "SMP" 
        WHEN pendidikan=3 THEN "SMA" 
        WHEN pendidikan=4 THEN "D1" 
        WHEN pendidikan=5 THEN "D2" 
        WHEN pendidikan=6 THEN "D3" 
        WHEN pendidikan=7 THEN "D4" 
        WHEN pendidikan=8 THEN "S1" 
        WHEN pendidikan=9 THEN "S2" 
        ELSE "S3" END 
        AS title')
        ->selectRaw('COUNT(*) AS total')
        ->where('status_karyawan', 0)
        ->groupBy('title')
        ->get();

        if(count($chart_pendidikan)) {
            foreach ($chart_pendidikan as $data) {
                if ($data->title !== null) {
                    $pendidikan[] = $data->title;
                    $total_pendidikan[] = $data->total;
                }
            }
        }else{
            $pendidikan[] = "";
            $total_pendidikan[] = "";
        }

        // dd($pendidikan);

        $chart_status_pernikahan = Karyawan::selectRaw('CASE 
        WHEN status_pernikahan=1 THEN "Belum Menikah" 
        WHEN status_pernikahan=2 THEN "Menikah" 
        WHEN status_pernikahan=3 THEN "Janda"
        ELSE "Duda" END 
        AS title')
        ->selectRaw('COUNT(*) AS total')
        ->where('status_karyawan', 0)
        ->groupBy('title')
        ->get();

        if(count($chart_status_pernikahan)) {
            foreach ($chart_status_pernikahan as $data) {
                if ($data->title !== null) {
                    $status_pernikahan[] = $data->title;
                    $total_status_pernikahan[] = $data->total;
                }
            }
        }else{
            $status_pernikahan[] = "";
            $total_status_pernikahan[] = "";
        }

        // dd($status_pernikahan);
        $chart_umur = Karyawan::selectRaw('CASE 
        WHEN umur >= 17 && umur <= 20 THEN "17-20" 
        WHEN umur >= 21 && umur <= 30 THEN "21-30" 
        WHEN umur >= 31 && umur <= 40 THEN "31-40" 
        WHEN umur >= 41 && umur <= 50 THEN "41-50" 
        ELSE "> 50" END 
        AS title')
        ->selectRaw('COUNT(*) AS total')
        ->where('status_karyawan', 0)
        ->groupBy('title')
        ->get();

        if(count($chart_umur)) {
            foreach ($chart_umur as $data) {
                if ($data->title !== null) {
                    $umur[] = $data->title;
                    $total_umur[] = $data->total;
                }
            }
        }else{
            $umur[] = "";
            $total_umur[] = "";
        }

        // $chart_komposisi_karyawan = Karyawan::selectRaw('CASE 
        // WHEN komposisi_karyawan=1 THEN "Director" 
        // WHEN komposisi_karyawan=2 THEN "Div Head" 
        // WHEN komposisi_karyawan=3 THEN "Dept Head" 
        // WHEN komposisi_karyawan=4 THEN "Sect Head" 
        // WHEN komposisi_karyawan=5 THEN "Head" 
        // WHEN komposisi_karyawan=6 THEN "Staff" 
        // ELSE "Non Staff" END 
        // AS title')
        // ->selectRaw('COUNT(*) AS total')
        // ->where('status_karyawan', 0)
        // ->groupBy('title')
        // ->get();

        // if(count($chart_komposisi_karyawan)) {
        //     foreach ($chart_komposisi_karyawan as $data) {
        //         if ($data->title !== null) {
        //             $komposisi_karyawan[] = $data->title;
        //             $total_komposisi_karyawan[] = $data->total;
        //         }
        //     }
        // }else{
        //     $komposisi_karyawan[] = "";
        //     $total_komposisi_karyawan[] = "";
        // }

        $chart_karyawan_phk = KaryawanPHK::selectRaw('CASE 
        WHEN penyebab_phk=1 THEN "Affair" 
        ELSE "Fraud" END 
        AS title')
        ->selectRaw('COUNT(*) AS total')
        ->groupBy('title')
        ->get();

        if(count($chart_karyawan_phk)) {
            foreach ($chart_karyawan_phk as $data) {
                if ($data->title !== null) {
                    $penyebab_phk[] = $data->title;
                    $total_penyebab_phk[] = $data->total;
                }
            }
        }else{
            $penyebab_phk[] = "";
            $total_penyebab_phk[] = "";
        }

        $chart_karyawan_resign = KaryawanResign::selectRaw('CASE 
        WHEN alasan_resign=1 THEN "Tidak Memenuhi Target" 
        WHEN alasan_resign=2 THEN "Mendapatkan Pekerjaan Lain" 
        WHEN alasan_resign=3 THEN "Melanjutkan Pendidikan" 
        WHEN alasan_resign=4 THEN "Faktor Keluarga"
        WHEN alasan_resign=5 THEN "Habis Kontrak"
        WHEN alasan_resign=6 THEN "Usia Pensiun"
        ELSE "Meninggal Dunia" END 
        AS title')
        ->selectRaw('COUNT(*) AS total')
        ->groupBy('title')
        ->get();

        if(count($chart_karyawan_resign)) {
            foreach ($chart_karyawan_resign as $data) {
                if ($data->title !== null) {
                    $alasan_resign[] = $data->title;
                    $total_alasan_resign[] = $data->total;
                }
            }
        }else{
            $alasan_resign[] = "";
            $total_alasan_resign[] = "";
        }

        // dd($total_penyebab_phk);

        
        //menampilkan data 1 bulan karyawan baru
        $bulan_lalu = date('Y-m-d', strtotime('-1 months'));
        $karyawan_baru = Karyawan::with('perusahaan', 'divisi', 'jabatan', 'posisi')->where('tanggal_masuk', '>=', $bulan_lalu)->where('status_karyawan', 0)->latest()->paginate(5)->onEachSide(1);

        //menampilkan data karyawan yang habis kontrak 2 bulan sebelumnya
        $dua_bulan = date('Y-m-d', strtotime('-2 months'));
        $karyawan_kontrak = Karyawan::with('perusahaan', 'divisi', 'jabatan', 'posisi')->where('akhir_kontrak', '>=', $dua_bulan)->where('status_karyawan', 0)
        ->orderBy('akhir_kontrak', 'asc')->latest()->paginate(5)->onEachSide(1);

        //menampilkan data karyawan yang mempunyai 3 pelanggaran atau lebih
        $data_pelanggaran = DB::table('karyawan')
                ->join('catatan_pelanggaran', 'karyawan.id', '=', 'catatan_pelanggaran.karyawan_id')
                ->join('master_perusahaan', 'karyawan.pt_id', '=', 'master_perusahaan.id' )
                ->join('master_divisi', 'karyawan.pt_id', '=', 'master_divisi.id' )
                ->join('master_jabatan', 'karyawan.pt_id', '=', 'master_jabatan.id' )
                ->join('master_posisi', 'karyawan.pt_id', '=', 'master_posisi.id' )
                ->select('karyawan.*', DB::raw('count(karyawan.id) as jumlah_pelanggaran'), 'catatan_pelanggaran.karyawan_id', 'master_perusahaan.nama_pt', 'master_divisi.nama_divisi', 'master_jabatan.nama_jabatan', 'master_posisi.nama_posisi')
                // ->select(array('catatan_pelanggaran.catatan'))
                ->having(DB::raw('count(karyawan.id)'), '>=', 3)
                ->groupBy('karyawan.id')
                ->orderBy('karyawan.nama_lengkap', 'desc')
                // ->where('karyawan.status_karyawan', 0)
                ->latest()->paginate(5)->onEachSide(1);

        //total karyawan aktif
        $total_karyawan_aktif = Karyawan::where('status_karyawan', 0)->count();
        // dd($total_karyawan_aktif);

        //menampilkan data karyawan termuda
        // $termuda = DB::table('karyawan')->where('umur', DB::raw("(select min(`umur`) from karyawan)"))->where('status_karyawan', 0)->first();
        $termuda = Karyawan::where('status_karyawan', 0)->orderBy('umur', 'asc')->first();

        //menampilkan data karyawan tertua
        // $tertua = DB::table('karyawan')->where('umur', DB::raw("(select max(`umur`) from karyawan)"))->where('status_karyawan', 0)->first();
        $tertua = Karyawan::where('status_karyawan', 0)->orderBy('umur', 'desc')->first();
        // $terlama = DB::table('karyawan')->where('masa_kerja_bulan', DB::raw("(select max(`masa_kerja_bulan`) from karyawan)"))->where('status_karyawan', 0)->first();
        $terlama = Karyawan::where('status_karyawan', 0)->orderBy('masa_kerja_bulan', 'desc')->first();
        // dd($terlama);

        return Inertia::render('Apps/Dashboard/Index',[
            'hari_ini'                  => $day,
            'total_karyawan_aktif'      => $total_karyawan_aktif,
            'karyawan_baru'             => $karyawan_baru,
            'karyawan_kontrak'          => $karyawan_kontrak,
            'data_pelanggaran'          => $data_pelanggaran,
            'termuda'                   => $termuda,
            'tertua'                    => $tertua,
            'terlama'                   => $terlama,
            'pt'                        => $pt,
            'total_pt'                  => $total_pt,
            'divisi'                    => $divisi,
            'total_divisi'              => $total_divisi,
            'jabatan'                   => $jabatan,
            'total_jabatan'             => $total_jabatan,
            'kota_penugasan'            => $kota_penugasan,
            'total_kota_penugasan'      => $total_kota_penugasan,
            'jenis_kelamin'             => $jenis_kelamin,
            'total_jenis_kelamin'       => $total_jenis_kelamin,
            'status_kerja'              => $status_kerja,
            'total_status_kerja'        => $total_status_kerja,
            'komposisi_generasi'        => $komposisi_generasi,
            'total_komposisi_generasi'  => $total_komposisi_generasi,
            'komposisi_peran'           => $komposisi_peran,
            'total_komposisi_peran'     => $total_komposisi_peran,
            'pendidikan'                => $pendidikan,
            'total_pendidikan'          => $total_pendidikan,
            'status_pernikahan'         => $status_pernikahan,
            'total_status_pernikahan'   => $total_status_pernikahan,
            'posisi'                    => $posisi,
            'total_posisi'              => $total_posisi,
            'penyebab_phk'              => $penyebab_phk,
            'total_penyebab_phk'        => $total_penyebab_phk,
            'umur'                      => $umur,
            'total_umur'                => $total_umur,
            'alasan_resign'             => $alasan_resign,
            'total_alasan_resign'       => $total_alasan_resign,
        ]);
    }
}  

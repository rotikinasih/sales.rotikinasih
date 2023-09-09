<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Carbon\Carbon;
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
        //menampilkan tanggal hari ini
        $day = date('d F Y');

        //jumlah karyawan berdasarkan PT
        $chart_pt = DB::table('karyawan')
            ->addSelect(DB::raw('master_perusahaan.nama_pt as title, COUNT(*) as total'))
            ->join('master_perusahaan', 'master_perusahaan.id', '=', 'karyawan.pt_id')
            ->groupBy('karyawan.pt_id')
            ->orderBy('total', 'DESC')
            ->where('status_karyawan', 0)
            ->get();
        if(count($chart_pt)) {
            foreach ($chart_pt as $data) {
                $pt[] = $data->title;
                $total_pt[]   = (int)$data->total;
            }
        }else {
            $pt[]   = "";
            $total_pt[]  = "";
        }


        //jumlah karyawan berdasarkan Divisi
        $chart_divisi = DB::table('karyawan')
            ->addSelect(DB::raw('master_divisi.nama_divisi as title, COUNT(*) as total'))
            ->join('master_divisi', 'master_divisi.id', '=', 'karyawan.divisi_id')
            ->groupBy('karyawan.divisi_id')
            ->orderBy('total', 'DESC')
            ->where('status_karyawan', 0)
            ->get();
        if(count($chart_divisi)) {
            foreach ($chart_divisi as $data) {
                $divisi[] = $data->title;
                $total_divisi[]   = (int)$data->total;
            }
        }else {
            $divisi[]   = "";
            $total_divisi[]  = "";
        }

        //jumlah karyawan berdasarkan Jabatan
        $chart_jabatan = DB::table('karyawan')
            ->addSelect(DB::raw('master_jabatan.nama_jabatan as title, COUNT(*) as total'))
            ->join('master_jabatan', 'master_jabatan.id', '=', 'karyawan.jabatan_id')
            ->groupBy('karyawan.jabatan_id')
            ->orderBy('total', 'DESC')
            ->where('status_karyawan', 0)
            ->get();
        if(count($chart_jabatan)) {
            foreach ($chart_jabatan as $data) {
                $jabatan[] = $data->title;
                $total_jabatan[]   = (int)$data->total;
            }
        }else {
            $jabatan[]   = "";
            $total_jabatan[]  = "";
        }

        $chart_kota_penugasan = DB::table('karyawan')
        ->addSelect(DB::raw('kota_penugasan as title, COUNT(*) as total'))
        ->groupBy('kota_penugasan')
        ->orderBy('total', 'DESC')
        ->where('status_karyawan', 0)
        ->get();
        if(count($chart_kota_penugasan)) {
            foreach ($chart_kota_penugasan as $data) {
                $kota_penugasan[] = $data->title;
                $total_kota_penugasan[]   = (int)$data->total;
            }
        }else {
            $kota_penugasan[]   = "";
            $total_kota_penugasan[]  = "";
        }

        $chart_jenis_kelamin = DB::table('karyawan')
        ->addSelect(DB::raw('jenis_kelamin as title, COUNT(*) as total'))
        ->groupBy('jenis_kelamin')
        ->orderBy('total', 'DESC')
        ->where('status_karyawan', 0)
        ->get();
        if(count($chart_jenis_kelamin)) {
            foreach ($chart_jenis_kelamin as $key => $data) {
                // if($data->title == null){                    
                //     $jenis_kelamin[]   = "";
                //     $total_jenis_kelamin[]  = "";
                // }
                if($data->title == 1){                    
                    $jenis_kelamin[$key] = 'Laki-laki';
                }

                if($data->title == 2){
                    $jenis_kelamin[$key] = 'Perempuan';
                }

                $total_jenis_kelamin[]   = (int)$data->total;
            }
        }else {
            $jenis_kelamin[]   = "";
            $total_jenis_kelamin[]  = "";
        }

        $chart_status_kerja = DB::table('karyawan')
        ->addSelect(DB::raw('status_kerja as title, COUNT(*) as total'))
        ->groupBy('status_kerja')
        ->orderBy('total', 'DESC')
        ->where('status_karyawan', 0)
        ->get();
        if(count($chart_status_kerja)) {
            foreach ($chart_status_kerja as $key => $data) {
                // if($data->title == null){                    
                //     $status_kerja[]   = "";
                //     $total_status_kerja[]  = "";
                // }
                if($data->title == 1){
                    $status_kerja[$key] = 'Kontrak';
                }

                if($data->title == 2){
                    $status_kerja[$key] = 'Tetap';
                }

                if($data->title == 3){
                    $status_kerja[$key] = 'Training';
                }
                
                $total_status_kerja[] = (int)$data->total;
            }
        }else {
            $status_kerja[]   = "";
            $total_status_kerja[]  = "";
        }

        $chart_komposisi_generasi = DB::table('karyawan')
        ->addSelect(DB::raw('komposisi_generasi as title, COUNT(*) as total'))
        ->groupBy('komposisi_generasi')
        ->orderBy('total', 'DESC')
        ->where('status_karyawan', 0)
        ->get();
        if(count($chart_komposisi_generasi)) {
            foreach ($chart_komposisi_generasi as $data) {
                $komposisi_generasi[] = $data->title;
                $total_komposisi_generasi[]   = (int)$data->total;
            }
        }else {
            $komposisi_generasi[]   = "";
            $total_komposisi_generasi[]  = "";
        }

        $chart_komposisi_peran = DB::table('karyawan')
        ->addSelect(DB::raw('komposisi_peran as title, COUNT(*) as total'))
        ->groupBy('komposisi_peran')
        ->orderBy('total', 'DESC')
        ->where('status_karyawan', 0)
        ->get();
        if(count($chart_komposisi_peran)) {
            foreach ($chart_komposisi_peran as $key => $data) {
                // if($data->title == null){                    
                //     $komposisi_peran[]   = "";
                //     $total_komposisi_peran[]  = "";
                // }
                if($data->title == 1){                    
                    $komposisi_peran[$key] = 'Support';
                }

                if($data->title == 2){
                    $komposisi_peran[$key] = 'Core';
                }

                $total_komposisi_peran[]   = (int)$data->total;
            }
        }else {
            $komposisi_peran[]   = "";
            $total_komposisi_peran[]  = "";
        }

        
        $chart_pendidikan = DB::table('karyawan')
        ->addSelect(DB::raw('pendidikan as title, COUNT(*) as total'))
        ->groupBy('pendidikan')
        ->orderBy('total', 'DESC')
        ->where('status_karyawan', 0)
        ->get();
        if(count($chart_pendidikan)) {
            foreach ($chart_pendidikan as $key => $data) {
                // if($data->title == null){                    
                //     $pendidikan[]   = "";
                //     $total_pendidikan[]  = "";
                // }
                if($data->title == 1){                    
                    $pendidikan[$key] = 'SD';
                }
                if($data->title == 2){
                    $pendidikan[$key] = 'SMP';
                }
                if($data->title == 3){
                    $pendidikan[$key] = 'SMA';
                }
                if($data->title == 4){
                    $pendidikan[$key] = 'D1';
                }
                if($data->title == 5){
                    $pendidikan[$key] = 'D2';
                }
                if($data->title == 6){
                    $pendidikan[$key] = 'D3';
                }
                if($data->title == 7){
                    $pendidikan[$key] = 'D4';
                }
                if($data->title == 8){
                    $pendidikan[$key] = 'S1';
                }
                if($data->title == 9){
                    $pendidikan[$key] = 'S2';
                }
                if($data->title == 10){
                    $pendidikan[$key] = 'S3';
                }
                $total_pendidikan[] = (int)$data->total;
            }
        }else {
            $pendidikan[]   = "";
            $total_pendidikan[]  = "";
        }

        // dd($pendidikan);

        $chart_status_pernikahan = DB::table('karyawan')
        ->addSelect(DB::raw('status_pernikahan as title, COUNT(*) as total'))
        ->groupBy('status_pernikahan')
        ->orderBy('total', 'DESC')
        ->where('status_karyawan', 0)
        ->get();
        if(count($chart_status_pernikahan)) {
            foreach ($chart_status_pernikahan as $key => $data) {
                // if($data->title == null){                    
                //     $status_pernikahan[]   = "";
                //     $total_status_pernikahan[]  = "";
                // }
                if($data->title == 1){                    
                    $status_pernikahan[$key] = 'Belum Menikah';
                    $total_status_pernikahan [$key]=$data->total++;
                }

                if($data->title == 2){
                    $status_pernikahan[$key] = 'Menikah';
                    $total_status_pernikahan [$key]=$data->total++;
                }

                if($data->title == 3){
                    $status_pernikahan[$key] = 'Janda';
                    $total_status_pernikahan [$key]=$data->total++;
                }
                
                if($data->title == 4){
                    $status_pernikahan[$key] = 'Duda';
                    $total_status_pernikahan [$key]=$data->total++;
                }

                // $total_status_pernikahan[]   = (int)$data->total;
            }
        }else {
            $status_pernikahan[]   = "";
            $total_status_pernikahan[]  = "";
        }

        // dd($status_pernikahan);
        $chart_umur = DB::table('karyawan')
        ->addSelect(DB::raw('umur as title, COUNT(*) as total'))
        ->groupBy('umur')
        ->orderBy('total', 'DESC')
        ->get();

        foreach ($chart_umur as $key => $data) {
            if ($data->title >= 17 && $data->title <= 20) {
                $total_umur [$key]=$data->total++;
                $umur[$key] = '17-20';
            } elseif ($data->title >= 21 && $data->title <= 30) {
                $total_umur [$key]=$data->total++;
                $umur[$key] = '21-30';
            } elseif ($data->title >= 31 && $data->title <= 40) {
                $total_umur [$key]=$data->total++;
                $umur[$key] = '31-40';
            } elseif ($data->title >= 41 && $data->title <= 50) {
                $total_umur [$key]=$data->total++;
                $umur[$key] = '41-50';
            } else {
                $total_umur [$key]=$data->total++;
                $umur[$key] = '50';
            }
        }
        
        // print_r($total_umur);
        // dd($total_umur);

        $chart_komposisi_karyawan = DB::table('karyawan')
        ->addSelect(DB::raw('komposisi_karyawan as title, COUNT(*) as total'))
        ->groupBy('komposisi_karyawan')
        ->orderBy('total', 'DESC')
        ->where('status_karyawan', 0)
        ->get();
        if(count($chart_komposisi_karyawan)) {
            foreach ($chart_komposisi_karyawan as $key => $data) {
                if($data->title == 1){                    
                    $komposisi_karyawan[$key] = 'Direktor';
                }
                if($data->title == 2){
                    $komposisi_karyawan[$key] = 'Div Head';
                }
                if($data->title == 3){
                    $komposisi_karyawan[$key] = 'Dept Head';
                }
                if($data->title == 4){
                    $komposisi_karyawan[$key] = 'Sect Head';
                }
                if($data->title == 5){
                    $komposisi_karyawan[$key] = 'Head';
                }
                if($data->title == 6){
                    $komposisi_karyawan[$key] = 'Staff';
                }
                if($data->title == 7){
                    $komposisi_karyawan[$key] = 'Non Staff';
                }
                $total_komposisi_karyawan[]   = (int)$data->total;
            }
        }else {
            $komposisi_karyawan[]   = "";
            $total_komposisi_karyawan[]  = "";
        }

        $chart_karyawan_phk = DB::table('karyawan_phk')
        ->addSelect(DB::raw('penyebab_phk as title, COUNT(*) as total'))
        ->groupBy('penyebab_phk')
        ->orderBy('total', 'DESC')
        ->get();
        if(count($chart_karyawan_phk)) {
            foreach ($chart_karyawan_phk as $key => $data) {
                if($data->title == 1){                    
                    $penyebab_phk[$key] = 'Affair';
                }
                if($data->title == 2){
                    $penyebab_phk[$key] = 'Fraud';
                }
                $total_penyebab_phk[]   = (int)$data->total;
            }
        }else {
            $penyebab_phk[]   = "";
            $total_penyebab_phk[]  = "";
        }
        // dd($total_penyebab_phk);

        
        //menampilkan data 1 bulan karyawan baru
        $bulan_lalu = date('Y-m-d', strtotime('-1 months'));
        $karyawan_baru = Karyawan::with('perusahaan')->where('tanggal_masuk', '>=', $bulan_lalu)->where('status_karyawan', 0)->latest()->paginate(10)->onEachSide(1);

        //menampilkan data karyawan yang habis kontrak 2 bulan sebelumnya
        $dua_bulan = date('Y-m-d', strtotime('-2 months'));
        $karyawan_kontrak = Karyawan::with('perusahaan')->where('akhir_kontrak', '>=', $dua_bulan)->where('status_karyawan', 0)->latest()->paginate(10)->onEachSide(1);

        //menampilkan data karyawan yang mempunyai 3 pelanggaran atau lebih
        $data_pelanggaran = DB::table('karyawan')
                ->join('catatan_pelanggaran', 'karyawan.id', '=', 'catatan_pelanggaran.karyawan_id')
                ->join('master_perusahaan', 'karyawan.pt_id', '=', 'master_perusahaan.id' )
                ->select('karyawan.*', DB::raw('count(karyawan.id) as jumlah_pelanggaran'), 'catatan_pelanggaran.karyawan_id', 'master_perusahaan.nama_pt')
                // ->select(array('catatan_pelanggaran.catatan'))
                ->having(DB::raw('count(karyawan.id)'), '>=', 3)
                ->groupBy('karyawan.id')
                ->orderBy('karyawan.nama_lengkap', 'desc')
                // ->where('karyawan.status_karyawan', 0)
                ->get();

        //menampilkan data karyawan termuda
        // $termuda = DB::table('karyawan')->where('umur', DB::raw("(select min(`umur`) from karyawan)"))->where('status_karyawan', 0)->first();
        $termuda = Karyawan::where('status_karyawan', 0)->orderBy('umur', 'asc')->first();

        //menampilkan data karyawan tertua
        // $tertua = DB::table('karyawan')->where('umur', DB::raw("(select max(`umur`) from karyawan)"))->where('status_karyawan', 0)->first();
        $tertua = Karyawan::where('status_karyawan', 0)->orderBy('umur', 'desc')->first();
        // $terlama = DB::table('karyawan')->where('masa_kerja_tahun', DB::raw("(select max(`masa_kerja_tahun`) from karyawan)"))->where('status_karyawan', 0)->first();
        $terlama = Karyawan::where('status_karyawan', 0)->orderBy('masa_kerja_tahun', 'desc')->first();

        return Inertia::render('Apps/Dashboard/Index',[
            'hari_ini'                  => $day,
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
            'komposisi_karyawan'        => $komposisi_karyawan,
            'total_komposisi_karyawan'  => $total_komposisi_karyawan,
            'penyebab_phk'              => $penyebab_phk,
            'total_penyebab_phk'        => $total_penyebab_phk,
            'umur'                      => $umur,
            'total_umur'                => $total_umur,
        ]);
    }
}  

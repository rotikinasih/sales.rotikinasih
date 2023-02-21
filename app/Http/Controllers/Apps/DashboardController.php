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

        //menampilkan data 1 bulan karyawan baru
        $bulan_lalu = date('Y-m-d', strtotime('-1 months'));
        $karyawan_baru = Karyawan::where('tanggal_masuk', '>=', $bulan_lalu)->latest()->paginate(10)->onEachSide(1);

        //menampilkan data karyawan yang habis kontrak 2 bulan sebelumnya
        $dua_bulan = date('Y-m-d', strtotime('-2 months'));
        $karyawan_kontrak = Karyawan::where('akhir_kontrak', '>=', $dua_bulan)->latest()->paginate(10)->onEachSide(1);

        //menampilkan data karyawan yang mempunyai 3 pelanggaran atau lebih
        $data_pelanggaran = DB::table('karyawan')
                ->join('catatan_pelanggaran', 'karyawan.id', '=', 'catatan_pelanggaran.karyawan_id')
                ->select('karyawan.*', DB::raw('count(karyawan.id) as jumlah_pelanggaran'), 'catatan_pelanggaran.karyawan_id')
                // ->select(array('catatan_pelanggaran.catatan'))
                ->having(DB::raw('count(karyawan.id)'), '>=', 3)
                ->groupBy('karyawan.id')
                ->orderBy('karyawan.nama_karyawan', 'desc')
                ->get();

        //menampilkan data karyawan termuda
        $termuda = DB::table('karyawan')->where('umur', DB::raw("(select min(`umur`) from karyawan)"))->first();

        //menampilkan data karyawan tertua
        $tertua = DB::table('karyawan')->where('umur', DB::raw("(select max(`umur`) from karyawan)"))->first();

        return Inertia::render('Apps/Dashboard/Index',[
            'hari_ini'          => $day,
            'karyawan_baru'     => $karyawan_baru,
            'karyawan_kontrak'  => $karyawan_kontrak,
            'data_pelanggaran'  => $data_pelanggaran,
            'termuda'           => $termuda,
            'tertua'            => $tertua,
            'pt'                => $pt,
            'total_pt'          => $total_pt,
            'divisi'            => $divisi,
            'total_divisi'      => $total_divisi,
            'jabatan'           => $jabatan,
            'total_jabatan'     => $total_jabatan,
        ]);
    }
}

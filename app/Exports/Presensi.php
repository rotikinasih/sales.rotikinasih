<?php

namespace App\Exports;

use App\Models\Pelatihan;
use App\Models\Presensi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class PresensiExport implements FromView
{/**
     * view
     *
     * @return View
     */
    public function view(): View
    {
        return view('exports.presensi', [
            'presensi' => Presensi::with('karyawan')->get(), 
            'waktu_sekarang' => date("d-m-Y H:i:s")
        ]);
    }
}

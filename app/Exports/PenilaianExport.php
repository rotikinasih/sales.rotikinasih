<?php

namespace App\Exports;

use App\Models\Pelatihan;
use App\Models\Penilaian;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class PenilaianExport implements FromView
{/**
     * view
     *
     * @return View
     */
    public function view(): View
    {
        return view('exports.penilaian', [
            'penilaian' => Penilaian::with('karyawan')->get(), 
            'waktu_sekarang' => date("d-m-Y H:i:s")
        ]);
    }
}

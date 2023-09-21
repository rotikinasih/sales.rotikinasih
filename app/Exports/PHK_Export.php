<?php

namespace App\Exports;

use App\Models\KaryawanPHK;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class PHK_Export implements FromView
{/**
     * view
     *
     * @return View
     */
    public function view(): View
    {
        return view('exports.phk', [
            'phk' => KaryawanPHK::with('karyawan')->get(), 
            'waktu_sekarang' => date("d-m-Y H:i:s")
        ]);
    }
}

<?php

namespace App\Exports;

use App\Models\Pelatihan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class PelatihanExport implements FromView
{/**
     * view
     *
     * @return View
     */
    public function view(): View
    {
        return view('exports.pelatihan', [
            'pelatihan' => Pelatihan::with('karyawan')->get(), 
            'waktu_sekarang' => date("d-m-Y H:i:s")
        ]);
    }
}

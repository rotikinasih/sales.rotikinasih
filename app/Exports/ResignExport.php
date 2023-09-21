<?php

namespace App\Exports;

use App\Models\KaryawanResign;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class ResignExport implements FromView
{/**
     * view
     *
     * @return View
     */
    public function view(): View
    {
        return view('exports.resign', [
            'resign' => KaryawanResign::with('karyawan')->get(), 
            'waktu_sekarang' => date("d-m-Y H:i:s")
        ]);
    }
}

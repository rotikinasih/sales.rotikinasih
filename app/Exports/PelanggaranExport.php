<?php

namespace App\Exports;

use App\Models\CatatanPelanggaran;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class PelanggaranExport implements FromView
{/**
     * view
     *
     * @return View
     */
    public function view(): View
    {
        return view('exports.pelanggaran', [
            'pelanggaran' => CatatanPelanggaran::with('karyawan')->orderBy('tanggal','DESC')->get(), 
            'waktu_sekarang' => date("d-m-Y H:i:s")
        ]);
    }
}

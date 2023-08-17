<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class KaryawanFormatExport implements FromView
{
    /**
     * view
     *
     * @return View
     */
    public function view(): View
    {
        return view('exports.formatkaryawan', [
            'karyawan' => Karyawan::with('perusahaan', 'divisi', 'jabatan')->get()
        ]);
    }
}

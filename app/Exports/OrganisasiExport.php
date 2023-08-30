<?php

namespace App\Exports;

use App\Models\RiwayatOrganisasi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class OrganisasiExport implements FromView
{/**
     * view
     *
     * @return View
     */
    public function view(): View
    {
        return view('exports.organisasi', [
            'organisasi' => RiwayatOrganisasi::with('karyawan', 'perusahaan', 'divisi', 'jabatan')->orderBy('tgl_masuk', 'DESC')->orderBy('tgl_masuk','DESC')->get(), 
            'waktu_sekarang' => date("d-m-Y H:i:s")
        ]);
    }
}

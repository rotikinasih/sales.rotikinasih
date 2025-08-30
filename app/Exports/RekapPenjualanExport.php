<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapPenjualanExport implements FromView
{
    protected $transaksis;
    protected $tanggal;

    public function __construct($transaksis, $tanggal)
    {
        $this->transaksis = $transaksis;
        $this->tanggal = $tanggal;
    }

    public function view(): View
    {
        return view('exports.rekap_excel', [
            'transaksis' => $this->transaksis,
            'tanggal' => $this->tanggal,
            'total' => $this->transaksis->sum('total'),
        ]);
    }
}

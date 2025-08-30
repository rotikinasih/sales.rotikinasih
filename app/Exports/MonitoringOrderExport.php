<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MonitoringOrderExport implements FromView
{
    protected $rekap;
    protected $tanggal;
    protected $kode;
    protected $totalPerProduk;

    public function __construct($rekap, $tanggal, $kode, $totalPerProduk = [])
    {
        $this->rekap = $rekap;
        $this->tanggal = $tanggal;
        $this->kode = $kode;
        $this->totalPerProduk = $totalPerProduk;
    }

    public function view(): View
    {
        return view('exports.monitoring_order_excel', [
            'rekap' => $this->rekap,
            'tanggal' => $this->tanggal,
            'kode' => $this->kode,
            'totalPerProduk' => $this->totalPerProduk,
        ]);
    }
}
<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanKeuanganExport implements FromView
{
    protected $data;
    protected $tanggal;
    protected $outlet_id;

    public function __construct($data, $tanggal, $outlet_id)
    {
        $this->data = $data;
        $this->tanggal = $tanggal;
        $this->outlet_id = $outlet_id;
    }

    public function view(): View
    {
        return view('exports.laporan_keuangan_excel', [
            'data' => $this->data,
            'tanggal' => $this->tanggal,
            'outlet_id' => $this->outlet_id,
        ]);
    }
}
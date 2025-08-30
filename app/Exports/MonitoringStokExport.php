<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MonitoringStokExport implements FromView
{
    protected $data;
    protected $total;
    protected $tanggal;

    public function __construct($data, $total, $tanggal)
    {
        $this->data = $data;
        $this->total = $total;
        $this->tanggal = $tanggal;
    }

    public function view(): View
    {
        return view('exports.monitoring_stok_excel', [
            'data' => $this->data,
            'total' => $this->total,
            'tanggal' => $this->tanggal,
        ]);
    }
}
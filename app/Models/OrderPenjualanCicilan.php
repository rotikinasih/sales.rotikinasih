<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPenjualanCicilan extends Model
{
    protected $table = 'order_penjualan_cicilan';
    protected $fillable = ['order_penjualan_id', 'nominal', 'cicilan_ke', 'tanggal'];

    public function orderPenjualan()
    {
        return $this->belongsTo(\App\Models\OrderPenjualan::class, 'order_penjualan_id');
    }
}

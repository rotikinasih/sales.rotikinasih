<?php

// app/Models/OrderPenjualanDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPenjualanDetail extends Model
{
    protected $fillable = [
        'order_penjualan_id',
        'master_produk_id',
        'jumlah_beli',
    ];

    public function master_produk()
{
    return $this->belongsTo(\App\Models\MasterProduk::class, 'master_produk_id');
}
    public function order_penjualan()
    {
        return $this->belongsTo(OrderPenjualan::class);
    }
}

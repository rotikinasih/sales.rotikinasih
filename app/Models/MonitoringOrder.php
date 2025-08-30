<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringOrder extends Model
{
    use HasFactory;

    protected $table = "monitoring_order";
    protected $guarded = ['id'];

    public function orderPenjualan()
    {
        return $this->belongsTo(OrderPenjualan::class, 'order_penjualan_id');
    }
    public function distribusiProduk()
{
    return $this->hasOne(DistribusiProduk::class);
}

}

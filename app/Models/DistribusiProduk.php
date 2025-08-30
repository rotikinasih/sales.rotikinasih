<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistribusiProduk extends Model
{
    protected $table = 'distribusi_produk';
    protected $fillable = [
        'monitoring_order_id',
        'order_penjualan_id',
        'status_distribusi',
        'jam_pengiriman',
        'created_id',
        'master_kendaraan_id', // <-- wajib ada!
    ];

    // Status distribusi: 1=Dikirim, 2=Diterima, 3=Ditolak

    public function monitoringOrder()
    {
        return $this->belongsTo(\App\Models\MonitoringOrder::class, 'monitoring_order_id');
    }

    public function orderPenjualan()
    {
        return $this->belongsTo(OrderPenjualan::class);
    }
    
    public function master_kendaraan() {
        return $this->belongsTo(MasterKendaraan::class, 'master_kendaraan_id');
    }
}

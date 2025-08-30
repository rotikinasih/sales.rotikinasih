<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterProduk extends Model
{
    use HasFactory;

    protected $table = "master_produk";

    protected $guarded = [
        'id'
    ];

    public function order_penjualan()
    {
        return $this->belongsToMany(
            OrderPenjualan::class,
            'order_penjualan_produk',
            'master_produk_id',
            'order_penjualan_id'
        )->withPivot('jumlah_beli');
    }
    public function inventory_stok()
{
    return $this->hasOne(InventoryStok::class, 'master_produk_id');
}
public function kategori()
{
    return $this->belongsTo(\App\Models\KategoriProduk::class, 'kategori_id');
}
public function outlet()
{
    return $this->belongsToMany(\App\Models\MasterOutlet::class, 'produk_outlet', 'produk_id', 'outlet_id');
}
public function tipe_outlet()
{
    return $this->belongsToMany(\App\Models\TipeOutlet::class, 'produk_tipe_outlet', 'produk_id', 'tipe_outlet_id');
}
}

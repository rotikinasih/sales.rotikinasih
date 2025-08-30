<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryStok extends Model
{
    protected $table = 'inventory_stok';
    protected $fillable = ['master_produk_id', 'stok', 'outlet_id'];

    public function master_produk()
    {
        return $this->belongsTo(MasterProduk::class, 'master_produk_id');
    }
}
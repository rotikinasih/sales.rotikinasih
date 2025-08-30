<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringStok extends Model
{
    use HasFactory;

    public function inventory_stok()
    {
        return $this->hasOne(InventoryStok::class, 'master_produk_id');
    }
}

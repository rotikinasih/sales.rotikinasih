<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKendaraan extends Model
{
    use HasFactory;

    protected $table = "master_kendaraan";

    protected $guarded = [
        'id'
    ];

    public function distribusiProduk()
    {
        return $this->hasMany(DistribusiProduk::class);
    }
}

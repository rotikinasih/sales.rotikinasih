<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPerusahaan extends Model
{
    use HasFactory;
    protected $table = "master_perusahaan";

    protected $guarded = [
        'id'
    ];

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }

    public function perusahaan()
    {
        return $this->hasMany(MasterPerusahaan::class);
    }

    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanResign extends Model
{
    use HasFactory;

    protected $table = "karyawan_resign";

    protected $guarded = [
        'id'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanPelanggaran extends Model
{
    use HasFactory;

    protected $table = "catatan_pelanggaran";

    protected $guarded = [
        'id'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }
}

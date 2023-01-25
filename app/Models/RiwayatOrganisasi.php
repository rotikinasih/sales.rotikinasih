<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatOrganisasi extends Model
{
    use HasFactory;

    protected $table = "riwayat_organisasi";

    protected $fillable = [
        'divisi_id', 'karyawan_id', 'tgl_gabung_grup', 'tgl_masuk', 'tgl_berakhir'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }

    public function divisi()
    {
        return $this->belongsTo(MasterDivisi::class, 'divisi_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatOrganisasi extends Model
{
    use HasFactory;

    protected $table = "riwayat_organisasi";

    protected $guarded = ['id'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }

    public function divisi()
    {
        return $this->belongsTo(MasterDivisi::class, 'divisi_id', 'id');
    }

    public function jabatan()
    {
        return $this->belongsTo(MasterJabatan::class, 'jabatan_id', 'id');
    }

    public function perusahaan()
    {
        return $this->belongsTo(MasterPerusahaan::class, 'pt_id', 'id');
    }
}

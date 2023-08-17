<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = "karyawan";

    protected $guarded = ['id'];

    public function perusahaan()
    {
        return $this->belongsTo(MasterPerusahaan::class, 'pt_id', 'id');
    }

    public function divisi()
    {
        return $this->belongsTo(MasterDivisi::class, 'divisi_id', 'id');
    }

    public function organisasi()
    {
        return $this->hasMany(RiwayatOrganisasi::class);
    }

    public function pelanggaran()
    {
        return $this->hasMany(CatatanPelanggaran::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(MasterJabatan::class, 'jabatan_id', 'id');
    }
}

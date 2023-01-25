<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = "karyawan";

    protected $fillable = [
        'nama_karyawan', 'nik_karyawan', 'status_kerja', 'divisi_id', 'pt_id', 'foto', 'tanggal_masuk', 'tanggal_kontrak', 'no_kk', 'nik_penduduk', 'grade', 'jabatan', 'no_hp', 'no_wa', 'no_bpjs_kesehatan', 'no_bpjs_ketenagakerjaan', 'gol_darah', 'email', 'tempat_lahir', 'tanggal_lahir', 'alamat_ktp','alamat_domisili', 'jenis_kelamin', 'status_pernikahan', 'pendidikan', 'nama_sekolah', 'kab_penugasan', 'rekening', 'ukuran_baju', 'no_sdr', 'hubungan'
    ];

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
}

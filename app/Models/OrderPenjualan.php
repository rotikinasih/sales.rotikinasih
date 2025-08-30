<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPenjualan extends Model
{
    use HasFactory;
    protected $table = "order_penjualan";

    protected $fillable = [
        'no_fraktur', 'nama', 'no_telp', 'alamat_pengiriman', 'tanggal_pengiriman', 'jam_pengiriman',
        'tanggal_pembuatan', 'status_pembayaran', 'keterangan', 'jumlah_bayar', 'total_bayar', 'kurang_bayar',
        'metode_pembayaran', 'tanggal_dp', 'keterangan_staf', 'penginput', 'lokasi', 'created_id', 'kode_distribusi', 'stok',
        'cicilan_dp', 'outlet_id'
    ];


    public function perusahaan()
    {
        return $this->hasMany(OrderPenjualan::class);
    }

    public function master_produk()
    {
        return $this->belongsTo(\App\Models\MasterProduk::class, 'master_produk_id');
    }

    public function details()
    {
        return $this->hasMany(\App\Models\OrderPenjualanDetail::class);
    }

    // Tambahkan relasi ke outlet jika ingin
    public function outlet()
    {
        return $this->belongsTo(\App\Models\MasterOutlet::class, 'outlet_id');
    }

    public function cicilan()
    {
        return $this->hasMany(OrderPenjualanCicilan::class);
    }
}

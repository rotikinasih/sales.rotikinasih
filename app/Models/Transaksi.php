<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'total',
        'pembayaran',
        'diskon',         // tambahkan
        'jumlah_bayar',    // tambahkan
        'pelanggan',       // tambahkan
        'outlet_id',
    ];

    public function items()
    {
        return $this->hasMany(TransaksiItem::class);
    }
}

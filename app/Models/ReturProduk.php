<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturProduk extends Model
{
    protected $table = 'retur_produk';
    protected $fillable = [
        'tanggal', 'produk_id', 'jumlah', 'harga', 'total', 'outlet_id'
    ];

    public function produk()
    {
        return $this->belongsTo(MasterProduk::class, 'produk_id');
    }
}
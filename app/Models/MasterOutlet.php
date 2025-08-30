<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterOutlet extends Model
{
    use HasFactory;

    protected $table = "master_outlet";

    protected $guarded = [
        'id'
    ];

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }
    
    public function tipe_outlet()
    {
        return $this->belongsTo(\App\Models\TipeOutlet::class, 'tipe_outlet_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPosisi extends Model
{
    use HasFactory;

    protected $table = "master_posisi";

    protected $guarded = [
        'id'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterCS extends Model
{
    use HasFactory;

    protected $table = 'master_cs';

    protected $fillable = [
        'nama',
        'no_hp',
        'status',
    ];
}

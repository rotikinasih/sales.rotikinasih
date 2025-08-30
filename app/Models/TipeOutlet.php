<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeOutlet extends Model
{
    use HasFactory;

    protected $table = "tipe_outlet";

    protected $guarded = [
        'id'
    ];
}

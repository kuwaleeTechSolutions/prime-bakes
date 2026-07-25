<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = ['name', 'rate', 'is_active'];

    protected $casts = ['rate' => 'float', 'is_active' => 'boolean'];
}

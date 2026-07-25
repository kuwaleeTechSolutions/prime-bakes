<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['name', 'code', 'exchange_rate'];

    protected $casts = ['exchange_rate' => 'float'];
}

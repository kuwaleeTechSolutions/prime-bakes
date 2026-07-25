<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrmSetting extends Model
{
    protected $table = 'hrm_settings';

    protected $fillable = ['checkin', 'checkout'];

    public static function current(): self
    {
        return static::first() ?? static::create(['checkin' => '9:00am', 'checkout' => '6:00pm']);
    }
}

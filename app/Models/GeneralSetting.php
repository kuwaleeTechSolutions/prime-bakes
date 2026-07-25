<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $fillable = [
        'site_title', 'site_logo', 'is_rtl', 'currency', 'currency_position',
        'staff_access', 'date_format', 'invoice_format', 'theme', 'cash_register',
    ];

    protected $casts = ['is_rtl' => 'boolean', 'cash_register' => 'boolean'];

    public static function current(): self
    {
        return static::first() ?? static::create([
            'site_title' => config('app.name', 'Retailo POS'),
            'currency' => 'INR',
            'currency_position' => 'prefix',
            'staff_access' => 'own_warehouse',
            'date_format' => 'd-m-Y',
            'invoice_format' => 'standard',
            'theme' => 'default.css',
            'cash_register' => true,
        ]);
    }
}

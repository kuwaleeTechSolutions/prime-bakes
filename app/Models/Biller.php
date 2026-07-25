<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biller extends Model
{
    protected $fillable = [
        'name', 'image', 'company_name', 'vat_number', 'email',
        'phone_number', 'address', 'city', 'state', 'postal_code', 'country', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

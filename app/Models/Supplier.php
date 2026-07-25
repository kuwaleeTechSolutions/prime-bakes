<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name', 'image', 'company_name', 'vat_number', 'email', 'phone_number',
        'address', 'city', 'state', 'postal_code', 'country', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Sum of grand_total minus paid_amount across all of this supplier's purchases —
    // the "amount we owe them" figure for the People/Accounting due report.
    public function getOutstandingDueAttribute(): float
    {
        return $this->purchases()->sum('grand_total') - $this->purchases()->sum('paid_amount');
    }
}

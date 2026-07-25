<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    protected $fillable = ['name', 'percentage', 'is_active'];

    protected $casts = ['percentage' => 'float', 'is_active' => 'boolean'];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Applies this group's discount percentage to a subtotal — used at POS checkout.
    public function applyDiscount(float $subtotal): float
    {
        return round($subtotal * (1 - $this->percentage / 100), 2);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['unit_code', 'unit_name', 'base_unit', 'operator', 'operation_value', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Converts a quantity expressed in THIS unit into the product's base unit,
    // using operator/operation_value (e.g. "Packet" = base * 12 → operator '*', value 12).
    public function toBaseQty(float $qty): float
    {
        return match ($this->operator) {
            '*' => $qty * $this->operation_value,
            '/' => $qty / $this->operation_value,
            default => $qty,
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'account_no', 'name', 'initial_balance', 'total_balance', 'note',
        'is_default', 'is_upi', 'is_card', 'is_cheque', 'is_gift', 'is_deposit', 'is_points', 'is_active',
    ];

    protected $casts = [
        'total_balance' => 'float',
        'is_active' => 'boolean',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function credit(float $amount): void
    {
        $this->increment('total_balance', $amount);
    }

    public function debit(float $amount): void
    {
        $this->decrement('total_balance', $amount);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    protected $fillable = [
        'card_no', 'amount', 'expense', 'customer_id', 'user_id',
        'expired_date', 'created_by', 'is_active',
    ];

    protected $casts = [
        'amount' => 'float',
        'expense' => 'float',
        'expired_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function recharges()
    {
        return $this->hasMany(GiftCardRecharge::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isExpired(): bool
    {
        return $this->expired_date && $this->expired_date->isPast();
    }

    public function redeem(float $amount, int $userId): void
    {
        if ($this->isExpired()) {
            throw new \RuntimeException("Gift card {$this->card_no} has expired.");
        }

        if ($this->amount < $amount) {
            throw new \RuntimeException("Gift card {$this->card_no} does not have enough balance.");
        }

        $this->decrement('amount', $amount);
        $this->increment('expense', $amount);
        $this->update(['user_id' => $userId]);
    }

    public function recharge(float $amount, int $userId): void
    {
        $this->increment('amount', $amount);
        $this->recharges()->create(['amount' => $amount, 'user_id' => $userId]);
    }

    public static function generateCardNo(): string
    {
        return 'GC-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
    }
}

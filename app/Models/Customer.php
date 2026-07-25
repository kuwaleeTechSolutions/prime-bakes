<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_group_id', 'user_id', 'name', 'company_name', 'email', 'phone_number',
        'tax_no', 'address', 'city', 'state', 'postal_code', 'country',
        'points', 'deposit', 'expense', 'is_active',
    ];

    protected $casts = [
        'points' => 'float',
        'deposit' => 'float',
        'expense' => 'float',
        'is_active' => 'boolean',
    ];

    public function group()
    {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('phone_number', 'like', "%{$term}%")
              ->orWhere('company_name', 'like', "%{$term}%");
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Loyalty / wallet helpers — used by Sales/POS and Accounting later
    |--------------------------------------------------------------------------
    | These mutate points/deposit/expense directly. Once the Sales module
    | exists, it should call these rather than incrementing the columns
    | inline, so the "what changed this customer's balance and why" logic
    | lives in one place.
    */

    public function addPoints(float $points): void
    {
        $this->increment('points', $points);
    }

    public function redeemPoints(float $points): void
    {
        if ($this->points < $points) {
            throw new \RuntimeException("Customer {$this->id} does not have enough points to redeem.");
        }

        $this->decrement('points', $points);
    }

    public function addDeposit(float $amount): void
    {
        $this->increment('deposit', $amount);
    }

    public function useDeposit(float $amount): void
    {
        if ($this->deposit < $amount) {
            throw new \RuntimeException("Customer {$this->id} does not have enough deposit balance.");
        }

        $this->decrement('deposit', $amount);
    }

    public function recordExpense(float $amount): void
    {
        $this->increment('expense', $amount);
    }
}

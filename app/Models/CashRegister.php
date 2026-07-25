<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    protected $fillable = ['cash_in_hand', 'user_id', 'warehouse_id', 'status'];

    protected $casts = ['status' => 'boolean', 'cash_in_hand' => 'float'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public static function openFor(User $user, int $warehouseId, float $openingCash): self
    {
        return static::create([
            'cash_in_hand' => $openingCash,
            'user_id' => $user->id,
            'warehouse_id' => $warehouseId,
            'status' => true,
        ]);
    }

    public function close(): void
    {
        $this->update(['status' => false]);
    }

    // Cash actually taken across this register's session — the figure you'd
    // reconcile against physically counted cash at close-out time.
    public function getCashSalesTotalAttribute(): float
    {
        return $this->payments()->where('paying_method', 'Cash')->sum('amount');
    }
}

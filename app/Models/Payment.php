<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'payment_reference', 'user_id', 'purchase_id', 'sale_id', 'cash_register_id',
        'account_id', 'amount', 'used_points', 'change', 'paying_method', 'payment_note',
    ];

    protected $casts = ['amount' => 'float', 'change' => 'float'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateReference(): string
    {
        return 'ppr-' . now()->format('Ymd') . '-' . now()->format('His');
    }
}

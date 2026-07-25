<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoneyTransfer extends Model
{
    protected $fillable = ['reference_no', 'from_account_id', 'to_account_id', 'amount', 'note'];

    protected $casts = ['amount' => 'float'];

    public function fromAccount()
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }

    public static function generateReferenceNo(): string
    {
        return 'mtr-' . now()->format('Ymd') . '-' . now()->format('His');
    }
}

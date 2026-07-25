<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftCardRecharge extends Model
{
    protected $fillable = ['gift_card_id', 'amount', 'user_id'];

    protected $casts = ['amount' => 'float'];

    public function giftCard()
    {
        return $this->belongsTo(GiftCard::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['user_id', 'from_date', 'to_date', 'note', 'is_approved'];

    protected $casts = ['from_date' => 'date', 'to_date' => 'date', 'is_approved' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDaysAttribute(): int
    {
        return $this->from_date->diffInDays($this->to_date) + 1;
    }
}

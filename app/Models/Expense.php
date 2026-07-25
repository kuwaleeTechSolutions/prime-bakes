<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'reference_no', 'expense_category_id', 'warehouse_id', 'account_id',
        'user_id', 'cash_register_id', 'amount', 'note',
    ];

    protected $casts = ['amount' => 'float'];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateReferenceNo(): string
    {
        return 'er-' . now()->format('Ymd') . '-' . now()->format('His');
    }
}

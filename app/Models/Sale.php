<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'reference_no', 'user_id', 'cash_register_id', 'customer_id', 'warehouse_id', 'biller_id',
        'item', 'total_qty', 'total_discount', 'total_tax', 'total_price', 'grand_total',
        'order_tax_rate', 'order_tax', 'order_discount_type', 'order_discount_value', 'order_discount',
        'coupon_id', 'coupon_discount', 'shipping_cost', 'sale_status', 'payment_status',
        'document', 'paid_amount', 'sale_note', 'staff_note',
    ];

    protected $casts = [
        'total_qty' => 'float',
        'grand_total' => 'float',
        'paid_amount' => 'float',
    ];

    public function lines()
    {
        return $this->hasMany(ProductSale::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function biller()
    {
        return $this->belongsTo(Biller::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getDueAttribute(): float
    {
        return $this->grand_total - $this->paid_amount;
    }

    public static function generateReferenceNo(): string
    {
        // 4-digit numeric style matches the source data's reference_no format (e.g. "6256").
        return (string) random_int(1000, 9999);
    }
}

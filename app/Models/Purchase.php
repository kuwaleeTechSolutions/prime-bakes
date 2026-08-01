<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'reference_no', 'invoice_number', 'user_id', 'warehouse_id', 'supplier_id', 'purchase_date',
        'item', 'total_qty', 'total_discount', 'total_tax', 'total_cost',
        'order_tax_rate', 'order_tax', 'order_discount', 'shipping_cost',
        'grand_total', 'paid_amount', 'status', 'payment_status', 'document', 'note',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'total_qty' => 'float',
        'grand_total' => 'float',
        'paid_amount' => 'float',
    ];

    public function lines()
    {
        return $this->hasMany(ProductPurchase::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
        return 'pr-' . now()->format('Ymd') . '-' . now()->format('His');
    }
}

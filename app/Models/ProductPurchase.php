<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPurchase extends Model
{
    protected $table = 'product_purchases';

    protected $fillable = [
        'purchase_id', 'product_id', 'product_batch_id', 'variant_id', 'imei_number',
        'qty', 'recieved', 'purchase_unit_id', 'net_unit_cost', 'discount',
        'tax_rate', 'tax', 'total',
    ];

    protected $casts = [
        'qty' => 'float',
        'recieved' => 'float',
        'net_unit_cost' => 'float',
        'total' => 'float',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseUnit()
    {
        return $this->belongsTo(Unit::class, 'purchase_unit_id');
    }
}

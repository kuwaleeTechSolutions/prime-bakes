<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDamage extends Model
{
    protected $table = 'product_damage';

    protected $fillable = [
        'damage_id', 'product_id', 'product_batch_id', 'variant_id', 'imei_number',
        'qty', 'purchase_unit_id', 'net_unit_cost', 'tax_rate', 'tax', 'total',
    ];

    public function damage()
    {
        return $this->belongsTo(Damage::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

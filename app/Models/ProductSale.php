<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    protected $table = 'product_sales';

    protected $fillable = [
        'sale_id', 'product_id', 'product_batch_id', 'variant_id', 'imei_number',
        'qty', 'sale_unit_id', 'net_unit_price', 'discount', 'tax_rate', 'tax', 'total',
    ];

    protected $casts = ['qty' => 'float', 'net_unit_price' => 'float', 'total' => 'float'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

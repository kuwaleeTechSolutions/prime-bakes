<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBatch extends Model
{
    protected $fillable = ['product_id', 'batch_no', 'expired_date', 'qty'];

    protected $casts = ['expired_date' => 'date', 'qty' => 'float'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

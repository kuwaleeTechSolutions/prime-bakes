<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'code', 'type', 'barcode_symbology', 'brand_id', 'category_id',
        'unit_id', 'purchase_unit_id', 'sale_unit_id', 'cost', 'price', 'qty',
        'alert_quantity', 'daily_sale_objective', 'promotion', 'promotion_price',
        'starting_date', 'last_date', 'tax_id', 'tax_method', 'image', 'file',
        'is_embeded', 'is_variant', 'is_batch', 'is_diffPrice', 'is_imei', 'featured',
        'product_details', 'is_active',
    ];

    protected $casts = [
        'cost' => 'float',
        'price' => 'float',
        'qty' => 'float',
        'alert_quantity' => 'float',
        'is_variant' => 'boolean',
        'is_batch' => 'boolean',
        'is_imei' => 'boolean',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function purchaseUnit()
    {
        return $this->belongsTo(Unit::class, 'purchase_unit_id');
    }

    public function saleUnit()
    {
        return $this->belongsTo(Unit::class, 'sale_unit_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    // product_warehouse.product_id is a string in the source schema — cast on the query side.
    public function stockRows()
    {
        return $this->hasMany(ProductWarehouse::class, 'product_id', 'id');
    }

    public function batches()
    {
        return $this->hasMany(ProductBatch::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes / accessors
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('code', 'like', "%{$term}%");
        });
    }

    // Total stock across all warehouses. For a single-warehouse figure,
    // filter stockRows() by warehouse_id instead — this is a convenience
    // accessor for admin/reporting views only, not for POS cart logic.
    public function getTotalStockAttribute(): float
    {
        return $this->stockRows()->sum('qty');
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->alert_quantity !== null && $this->total_stock <= $this->alert_quantity;
    }
}

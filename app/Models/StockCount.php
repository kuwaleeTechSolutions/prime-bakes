<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockCount extends Model
{
    protected $fillable = [
        'reference_no', 'warehouse_id', 'category_id', 'brand_id', 'user_id',
        'type', 'initial_file', 'final_file', 'note', 'is_adjusted',
    ];

    protected $casts = ['is_adjusted' => 'boolean'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateReferenceNo(): string
    {
        return 'scr-' . now()->format('Ymd') . '-' . now()->format('His');
    }
}

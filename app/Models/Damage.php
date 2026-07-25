<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Damage extends Model
{
    protected $fillable = [
        'reference_no', 'user_id', 'status', 'from_warehouse_id', 'to_warehouse_id',
        'item', 'total_qty', 'total_tax', 'total_cost', 'disposal_cost', 'grand_total',
        'document', 'note',
    ];

    public function lines()
    {
        return $this->hasMany(ProductDamage::class);
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateReferenceNo(): string
    {
        return 'dmg-' . now()->format('Ymd') . '-' . now()->format('His');
    }
}

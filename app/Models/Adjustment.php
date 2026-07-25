<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    protected $fillable = ['reference_no', 'warehouse_id', 'document', 'total_qty', 'item', 'note'];

    public function lines()
    {
        return $this->hasMany(ProductAdjustment::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public static function generateReferenceNo(): string
    {
        return 'adr-' . now()->format('Ymd') . '-' . now()->format('His');
    }
}

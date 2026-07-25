<?php

namespace App\Services;

use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;

/**
 * Every module that touches inventory (Purchases, Sales, Transfers, Damages,
 * Adjustments, Stock Count) MUST go through this service to mutate
 * product_warehouse, rather than writing to it directly. Centralizing this:
 *   - guarantees stock never goes negative silently
 *   - keeps locking/consistency logic in one place (see the lockForUpdate below)
 *   - makes it possible to add stock-movement logging later without touching
 *     every module that currently writes stock
 */
class StockService
{
    public function increment(
        int|string $productId,
        int $warehouseId,
        float $qty,
        ?int $productBatchId = null,
        ?int $variantId = null,
    ): ProductWarehouse {
        return DB::transaction(function () use ($productId, $warehouseId, $qty, $productBatchId, $variantId) {
            $row = ProductWarehouse::query()
                ->where('product_id', $productId)
                ->where('warehouse_id', $warehouseId)
                ->where('product_batch_id', $productBatchId)
                ->where('variant_id', $variantId)
                ->lockForUpdate()
                ->first();

            if ($row) {
                $row->increment('qty', $qty);
                return $row->fresh();
            }

            return ProductWarehouse::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'product_batch_id' => $productBatchId,
                'variant_id' => $variantId,
                'qty' => $qty,
            ]);
        });
    }

    /**
     * @throws \RuntimeException if there isn't enough stock to decrement.
     */
    public function decrement(
        int|string $productId,
        int $warehouseId,
        float $qty,
        ?int $productBatchId = null,
        ?int $variantId = null,
    ): ProductWarehouse {
        return DB::transaction(function () use ($productId, $warehouseId, $qty, $productBatchId, $variantId) {
            $row = ProductWarehouse::query()
                ->where('product_id', $productId)
                ->where('warehouse_id', $warehouseId)
                ->where('product_batch_id', $productBatchId)
                ->where('variant_id', $variantId)
                ->lockForUpdate()
                ->first();

            if (! $row || $row->qty < $qty) {
                throw new \RuntimeException("Insufficient stock for product {$productId} in warehouse {$warehouseId}.");
            }

            $row->decrement('qty', $qty);
            return $row->fresh();
        });
    }

    /** Move stock from one warehouse to another as a single atomic operation. */
    public function transfer(
        int|string $productId,
        int $fromWarehouseId,
        int $toWarehouseId,
        float $qty,
        ?int $productBatchId = null,
        ?int $variantId = null,
    ): void {
        DB::transaction(function () use ($productId, $fromWarehouseId, $toWarehouseId, $qty, $productBatchId, $variantId) {
            $this->decrement($productId, $fromWarehouseId, $qty, $productBatchId, $variantId);
            $this->increment($productId, $toWarehouseId, $qty, $productBatchId, $variantId);
        });
    }

    public function stockOf(int|string $productId, int $warehouseId): float
    {
        return (float) ProductWarehouse::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->sum('qty');
    }
}

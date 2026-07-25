<?php

namespace App\Livewire\StockCounts;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockCount;
use App\Models\Warehouse;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

/**
 * Simplified relative to the source app: the original schema stores count
 * sheets as CSV files (initial_file/final_file) generated/parsed outside the
 * database. This component reproduces the same workflow (snapshot expected
 * qty → record counted qty → auto-reconcile the difference) directly against
 * product_warehouse instead of round-tripping through CSV files, which is
 * simpler to keep correct. initial_file/final_file are left null; extend this
 * if you specifically need file-based import/export for offline counting.
 */
class Form extends Component
{
    public ?int $warehouse_id = null;
    public ?int $category_id = null;
    public ?int $brand_id = null;
    public string $type = 'full';
    public string $note = '';

    public bool $started = false;

    // Each line: product_id, product_name, system_qty, counted_qty
    public array $lines = [];

    public function startCount(): void
    {
        $this->validate(['warehouse_id' => ['required', 'exists:warehouses,id']]);

        $products = Product::active()
            ->when($this->category_id, fn ($q) => $q->where('category_id', $this->category_id))
            ->when($this->brand_id, fn ($q) => $q->where('brand_id', $this->brand_id))
            ->get();

        $stockService = app(StockService::class);

        $this->lines = $products->map(fn ($product) => [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'system_qty' => $stockService->stockOf($product->id, $this->warehouse_id),
            'counted_qty' => $stockService->stockOf($product->id, $this->warehouse_id),
        ])->toArray();

        $this->type = ($this->category_id || $this->brand_id) ? 'partial' : 'full';
        $this->started = true;
    }

    public function save()
    {
        $stockService = app(StockService::class);

        DB::transaction(function () use ($stockService) {
            $stockCount = StockCount::create([
                'reference_no' => StockCount::generateReferenceNo(),
                'warehouse_id' => $this->warehouse_id,
                'category_id' => $this->category_id,
                'brand_id' => $this->brand_id,
                'user_id' => auth()->id(),
                'type' => $this->type,
                'note' => $this->note ?: null,
                'is_adjusted' => false,
            ]);

            $adjusted = false;

            foreach ($this->lines as $line) {
                $diff = $line['counted_qty'] - $line['system_qty'];

                if ($diff == 0) {
                    continue;
                }

                $adjusted = true;

                if ($diff > 0) {
                    $stockService->increment($line['product_id'], $this->warehouse_id, $diff);
                } else {
                    $stockService->decrement($line['product_id'], $this->warehouse_id, abs($diff));
                }
            }

            $stockCount->update(['is_adjusted' => $adjusted]);
        });

        session()->flash('success', 'Stock count reconciled — any differences have been applied.');

        return redirect()->route('stock-counts.index');
    }

    public function render()
    {
        return view('livewire.stock-counts.form', [
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
            'categories' => Category::active()->orderBy('name')->get(),
            'brands' => Brand::active()->orderBy('title')->get(),
        ]);
    }
}

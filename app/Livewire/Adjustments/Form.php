<?php

namespace App\Livewire\Adjustments;

use App\Models\Adjustment;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Form extends Component
{
    public ?int $warehouse_id = null;
    public string $note = '';

    // Each line: product_id, product_name, qty, action ('+' or '-'), current_qty
    public array $lines = [];
    public string $productSearch = '';

    #[Computed]
    public function productResults()
    {
        if (! $this->warehouse_id || strlen($this->productSearch) < 2) {
            return collect();
        }

        return Product::active()->search($this->productSearch)->limit(8)->get();
    }

    public function addProduct(int $productId): void
    {
        if (! $this->warehouse_id) {
            $this->addError('warehouse_id', 'Select a warehouse first.');
            return;
        }

        $product = Product::findOrFail($productId);
        $current = app(StockService::class)->stockOf($product->id, $this->warehouse_id);

        $this->lines[] = [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'qty' => 1,
            'action' => '+',
            'current_qty' => $current,
        ];

        $this->productSearch = '';
    }

    public function removeLine(int $index): void
    {
        unset($this->lines[$index]);
        $this->lines = array_values($this->lines);
    }

    public function save()
    {
        $this->validate([
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'lines' => ['required', 'array', 'min:1'],
        ], [
            'lines.required' => 'Add at least one product to adjust.',
        ]);

        $stockService = app(StockService::class);

        // Pre-check subtractions won't go negative, same reasoning as Transfers::save().
        foreach ($this->lines as $line) {
            if ($line['action'] === '-') {
                $available = $stockService->stockOf($line['product_id'], $this->warehouse_id);
                if ($available < $line['qty']) {
                    $this->addError('lines', "Can't subtract {$line['qty']} of \"{$line['product_name']}\" — only {$available} in stock.");
                    return;
                }
            }
        }

        DB::transaction(function () use ($stockService) {
            $adjustment = Adjustment::create([
                'reference_no' => Adjustment::generateReferenceNo(),
                'warehouse_id' => $this->warehouse_id,
                'total_qty' => collect($this->lines)->sum('qty'),
                'item' => count($this->lines),
                'note' => $this->note ?: null,
            ]);

            foreach ($this->lines as $line) {
                $adjustment->lines()->create([
                    'product_id' => $line['product_id'],
                    'qty' => $line['qty'],
                    'action' => $line['action'],
                ]);

                if ($line['action'] === '+') {
                    $stockService->increment($line['product_id'], $this->warehouse_id, $line['qty']);
                } else {
                    $stockService->decrement($line['product_id'], $this->warehouse_id, $line['qty']);
                }
            }
        });

        session()->flash('success', 'Adjustment applied — stock updated.');

        return redirect()->route('adjustments.index');
    }

    public function render()
    {
        return view('livewire.adjustments.form', [
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}

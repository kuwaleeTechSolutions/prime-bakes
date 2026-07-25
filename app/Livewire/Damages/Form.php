<?php

namespace App\Livewire\Damages;

use App\Models\Damage;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Form extends Component
{
    public ?int $from_warehouse_id = null;
    public float $disposal_cost = 0;
    public string $note = '';

    // Each line: product_id, product_name, qty, purchase_unit_id, net_unit_cost, tax_rate, available_qty
    public array $lines = [];
    public string $productSearch = '';

    #[Computed]
    public function productResults()
    {
        if (! $this->from_warehouse_id || strlen($this->productSearch) < 2) {
            return collect();
        }

        return Product::active()->search($this->productSearch)->limit(8)->get();
    }

    public function addProduct(int $productId): void
    {
        if (! $this->from_warehouse_id) {
            $this->addError('from_warehouse_id', 'Select a warehouse first.');
            return;
        }

        $product = Product::findOrFail($productId);
        $stock = app(StockService::class)->stockOf($product->id, $this->from_warehouse_id);

        $this->lines[] = [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'qty' => 1,
            'purchase_unit_id' => $product->purchase_unit_id,
            'net_unit_cost' => $product->cost,
            'tax_rate' => $product->tax?->rate ?? 0,
            'available_qty' => $stock,
        ];

        $this->productSearch = '';
    }

    public function removeLine(int $index): void
    {
        unset($this->lines[$index]);
        $this->lines = array_values($this->lines);
    }

    #[Computed]
    public function lineTotals(): array
    {
        return collect($this->lines)->map(fn ($line) => round($line['qty'] * $line['net_unit_cost'], 2))->toArray();
    }

    #[Computed]
    public function grandTotal(): float
    {
        return round(array_sum($this->lineTotals) + $this->disposal_cost, 2);
    }

    public function save()
    {
        $this->validate([
            'from_warehouse_id' => ['required', 'exists:warehouses,id'],
            'lines' => ['required', 'array', 'min:1'],
        ], [
            'lines.required' => 'Add at least one damaged product.',
        ]);

        $stockService = app(StockService::class);

        foreach ($this->lines as $line) {
            $available = $stockService->stockOf($line['product_id'], $this->from_warehouse_id);
            if ($available < $line['qty']) {
                $this->addError('lines', "Not enough stock of \"{$line['product_name']}\" to write off (have {$available}, need {$line['qty']}).");
                return;
            }
        }

        DB::transaction(function () use ($stockService) {
            $damage = Damage::create([
                'reference_no' => Damage::generateReferenceNo(),
                'user_id' => auth()->id(),
                'status' => 'completed',
                'from_warehouse_id' => $this->from_warehouse_id,
                'item' => count($this->lines),
                'total_qty' => collect($this->lines)->sum('qty'),
                'total_tax' => 0,
                'total_cost' => array_sum($this->lineTotals),
                'disposal_cost' => $this->disposal_cost ?: null,
                'grand_total' => $this->grandTotal,
                'note' => $this->note ?: null,
            ]);

            foreach ($this->lines as $index => $line) {
                $damage->lines()->create([
                    'product_id' => $line['product_id'],
                    'qty' => $line['qty'],
                    'purchase_unit_id' => $line['purchase_unit_id'],
                    'net_unit_cost' => $line['net_unit_cost'],
                    'tax_rate' => $line['tax_rate'],
                    'tax' => 0,
                    'total' => $this->lineTotals[$index] ?? 0,
                ]);

                $stockService->decrement($line['product_id'], $this->from_warehouse_id, $line['qty']);
            }
        });

        session()->flash('success', 'Damaged stock written off.');

        return redirect()->route('damages.index');
    }

    public function render()
    {
        return view('livewire.damages.form', [
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}

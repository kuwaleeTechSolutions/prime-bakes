<?php

namespace App\Livewire\Transfers;

use App\Models\Product;
use App\Models\Transfer;
use App\Models\Warehouse;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Form extends Component
{
    public ?int $from_warehouse_id = null;
    public ?int $to_warehouse_id = null;
    public float $shipping_cost = 0;
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
            $this->addError('from_warehouse_id', 'Select the source warehouse first.');
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
        return collect($this->lines)->map(function ($line) {
            $subtotal = $line['qty'] * $line['net_unit_cost'];
            $tax = $subtotal * ($line['tax_rate'] / 100);
            return round($subtotal + $tax, 2);
        })->toArray();
    }

    #[Computed]
    public function grandTotal(): float
    {
        return round(array_sum($this->lineTotals) + $this->shipping_cost, 2);
    }

    public function save()
    {
        $this->validate([
            'from_warehouse_id' => ['required', 'exists:warehouses,id', 'different:to_warehouse_id'],
            'to_warehouse_id' => ['required', 'exists:warehouses,id'],
            'lines' => ['required', 'array', 'min:1'],
        ], [
            'from_warehouse_id.different' => 'Source and destination warehouses must be different.',
            'lines.required' => 'Add at least one product to transfer.',
        ]);

        // Check every line has enough stock BEFORE writing anything, so a partial
        // transfer never gets created if line 5 of 6 would fail.
        $stockService = app(StockService::class);
        foreach ($this->lines as $line) {
            $available = $stockService->stockOf($line['product_id'], $this->from_warehouse_id);
            if ($available < $line['qty']) {
                $this->addError('lines', "Not enough stock of \"{$line['product_name']}\" in the source warehouse (have {$available}, need {$line['qty']}).");
                return;
            }
        }

        DB::transaction(function () use ($stockService) {
            $transfer = Transfer::create([
                'reference_no' => Transfer::generateReferenceNo(),
                'user_id' => auth()->id(),
                'status' => 'completed',
                'from_warehouse_id' => $this->from_warehouse_id,
                'to_warehouse_id' => $this->to_warehouse_id,
                'item' => count($this->lines),
                'total_qty' => collect($this->lines)->sum('qty'),
                'total_tax' => 0,
                'total_cost' => array_sum($this->lineTotals),
                'shipping_cost' => $this->shipping_cost,
                'grand_total' => $this->grandTotal,
                'note' => $this->note ?: null,
            ]);

            foreach ($this->lines as $index => $line) {
                $transfer->lines()->create([
                    'product_id' => $line['product_id'],
                    'qty' => $line['qty'],
                    'purchase_unit_id' => $line['purchase_unit_id'],
                    'net_unit_cost' => $line['net_unit_cost'],
                    'tax_rate' => $line['tax_rate'],
                    'tax' => 0,
                    'total' => $this->lineTotals[$index] ?? 0,
                ]);

                $stockService->transfer($line['product_id'], $this->from_warehouse_id, $this->to_warehouse_id, $line['qty']);
            }
        });

        session()->flash('success', 'Transfer completed — stock moved.');

        return redirect()->route('transfers.index');
    }

    public function render()
    {
        return view('livewire.transfers.form', [
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}

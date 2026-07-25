<?php

namespace App\Livewire\Purchases;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Form extends Component
{
    #[Locked]
    public ?int $purchaseId = null;

    #[Locked]
    public ?string $originalStatus = null;

    public ?int $warehouse_id = null;
    public ?int $supplier_id = null;
    public string $invoice_number = '';
    public float $order_discount = 0;
    public float $order_tax_rate = 0;
    public float $shipping_cost = 0;
    public float $paid_amount = 0;
    public string $status = 'pending';
    public string $payment_status = 'unpaid';
    public string $note = '';

    // Each line: product_id, product_name, qty, purchase_unit_id, net_unit_cost, discount, tax_rate
    public array $lines = [];

    public string $productSearch = '';

    public function mount(?Purchase $purchase = null): void
    {
        if ($purchase?->exists) {
            $this->purchaseId = $purchase->id;
            $this->originalStatus = $purchase->status;
            $this->fill($purchase->only([
                'warehouse_id', 'supplier_id', 'invoice_number', 'order_discount',
                'order_tax_rate', 'shipping_cost', 'paid_amount', 'status', 'payment_status', 'note',
            ]));

            $this->lines = $purchase->lines->map(fn ($line) => [
                'product_id' => $line->product_id,
                'product_name' => $line->product->name,
                'qty' => $line->qty,
                'purchase_unit_id' => $line->purchase_unit_id,
                'net_unit_cost' => $line->net_unit_cost,
                'discount' => $line->discount,
                'tax_rate' => $line->tax_rate,
            ])->toArray();
        }
    }

    #[Computed]
    public function productResults()
    {
        if (strlen($this->productSearch) < 2) {
            return collect();
        }

        return Product::active()
            ->search($this->productSearch)
            ->limit(8)
            ->get();
    }

    public function addProduct(int $productId): void
    {
        $product = Product::findOrFail($productId);

        $this->lines[] = [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'qty' => 1,
            'purchase_unit_id' => $product->purchase_unit_id,
            'net_unit_cost' => $product->cost,
            'discount' => 0,
            'tax_rate' => $product->tax?->rate ?? 0,
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
            $subtotal = $line['qty'] * $line['net_unit_cost'] - $line['discount'];
            $tax = $subtotal * ($line['tax_rate'] / 100);
            return round($subtotal + $tax, 2);
        })->toArray();
    }

    #[Computed]
    public function totals(): array
    {
        $totalQty = collect($this->lines)->sum('qty');
        $totalDiscount = collect($this->lines)->sum('discount');
        $lineTotals = $this->lineTotals;
        $subtotal = array_sum($lineTotals);
        $orderTax = $subtotal * ($this->order_tax_rate / 100);
        $grandTotal = $subtotal + $orderTax + $this->shipping_cost - $this->order_discount;

        return [
            'item_count' => count($this->lines),
            'total_qty' => $totalQty,
            'total_discount' => $totalDiscount,
            'total_cost' => $subtotal,
            'order_tax' => round($orderTax, 2),
            'grand_total' => round(max($grandTotal, 0), 2),
        ];
    }

    public function save()
    {
        $this->validate([
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'lines' => ['required', 'array', 'min:1'],
        ], [
            'lines.required' => 'Add at least one product line before saving.',
        ]);

        $totals = $this->totals;

        DB::transaction(function () use ($totals) {
            $purchase = Purchase::updateOrCreate(['id' => $this->purchaseId], [
                'reference_no' => $this->purchaseId
                    ? Purchase::find($this->purchaseId)->reference_no
                    : Purchase::generateReferenceNo(),
                'invoice_number' => $this->invoice_number ?: null,
                'user_id' => auth()->id(),
                'warehouse_id' => $this->warehouse_id,
                'supplier_id' => $this->supplier_id,
                'item' => $totals['item_count'],
                'total_qty' => $totals['total_qty'],
                'total_discount' => $totals['total_discount'],
                'total_tax' => $totals['order_tax'],
                'total_cost' => $totals['total_cost'],
                'order_tax_rate' => $this->order_tax_rate,
                'order_tax' => $totals['order_tax'],
                'order_discount' => $this->order_discount,
                'shipping_cost' => $this->shipping_cost,
                'grand_total' => $totals['grand_total'],
                'paid_amount' => $this->paid_amount,
                'status' => $this->status,
                'payment_status' => $this->payment_status,
                'note' => $this->note ?: null,
            ]);

            // Replace line items wholesale — simplest correct approach for a form
            // that re-renders the full line set each save.
            $purchase->lines()->delete();

            foreach ($this->lines as $index => $line) {
                $purchase->lines()->create([
                    'product_id' => $line['product_id'],
                    'qty' => $line['qty'],
                    'recieved' => $line['qty'], // assume full receipt unless a partial-receiving workflow is added later
                    'purchase_unit_id' => $line['purchase_unit_id'],
                    'net_unit_cost' => $line['net_unit_cost'],
                    'discount' => $line['discount'],
                    'tax_rate' => $line['tax_rate'],
                    'tax' => round(($line['qty'] * $line['net_unit_cost'] - $line['discount']) * ($line['tax_rate'] / 100), 2),
                    'total' => $this->lineTotals[$index] ?? 0,
                ]);
            }

            // Only push stock in the moment status transitions INTO "received" —
            // never on every save, or re-saving a received purchase would double-count stock.
            if ($this->status === 'received' && $this->originalStatus !== 'received') {
                $stock = app(StockService::class);
                foreach ($this->lines as $line) {
                    $stock->increment($line['product_id'], $this->warehouse_id, $line['qty']);
                }
            }
        });

        session()->flash('success', $this->purchaseId ? 'Purchase updated.' : 'Purchase created.');

        return redirect()->route('purchases.index');
    }

    public function render()
    {
        return view('livewire.purchases.form', [
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
            'suppliers' => Supplier::active()->orderBy('name')->get(),
        ]);
    }
}

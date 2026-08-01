<?php

namespace App\Livewire\Pos;

use App\Models\Account;
use App\Models\CashRegister;
use App\Models\Customer;
use App\Models\Product;
use App\Services\SaleService;
use App\Services\StockService;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Terminal extends Component
{
    public CashRegister $cashRegister;

    public string $productSearch = '';
    public ?int $customer_id = null;
    public ?int $biller_id = null;
    public string $sale_date;

    // Each: product_id, product_name, qty, sale_unit_id, sale_unit_code, net_unit_price, discount, tax_rate, available_qty
    public array $cart = [];

    public float $order_discount = 0;
    public float $shipping_cost = 0;

    public string $paying_method = 'Cash';
    public ?int $account_id = null;
    public float $tendered = 0;

    public bool $showPayment = false;

    public function mount(CashRegister $cashRegister): void
    {
        $this->cashRegister = $cashRegister;
        $this->sale_date = now()->toDateString();

        // Falls back to the first active customer (seed a "Walk-in Customer" row)
        // if no default is configured yet — see README.
        $this->customer_id = Customer::where('name', 'Walk-in Customer')->value('id')
            ?? Customer::active()->value('id');

        $this->account_id = Account::active()->where('is_default', true)->value('id')
            ?? Account::active()->value('id');
    }

    #[Computed]
    public function productResults()
    {
        if (strlen($this->productSearch) < 1) {
            return collect();
        }

        return Product::active()
            ->with('saleUnit')
            ->search($this->productSearch)
            ->limit(12)
            ->get();
    }

    public function addProduct(int $productId): void
    {
        $product = Product::with('saleUnit')->findOrFail($productId);
        $available = app(StockService::class)->stockOf($product->id, $this->cashRegister->warehouse_id);

        if ($available <= 0) {
            $this->addError('cart', "\"{$product->name}\" is out of stock in this warehouse.");
            return;
        }

        $existingIndex = collect($this->cart)->search(fn ($line) => $line['product_id'] === $product->id);

        if ($existingIndex !== false) {
            if ($this->cart[$existingIndex]['qty'] < $available) {
                $this->cart[$existingIndex]['qty']++;
            }
        } else {
            $this->cart[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'qty' => 1,
                'sale_unit_id' => $product->sale_unit_id,
                'sale_unit_code' => $product->saleUnit?->unit_code,
                'net_unit_price' => $product->price,
                'discount' => 0,
                'tax_rate' => $product->tax?->rate ?? 0,
                'available_qty' => $available,
            ];
        }

        $this->productSearch = '';
    }

    public function incrementLine(int $index): void
    {
        if ($this->cart[$index]['qty'] < $this->cart[$index]['available_qty']) {
            $this->cart[$index]['qty']++;
        }
    }

    public function decrementLine(int $index): void
    {
        if ($this->cart[$index]['qty'] > 1) {
            $this->cart[$index]['qty']--;
        } else {
            $this->removeLine($index);
        }
    }

    public function removeLine(int $index): void
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function clearCart(): void
    {
        $this->cart = [];
    }

    #[Computed]
    public function lineTotals(): array
    {
        return collect($this->cart)->map(function ($line) {
            $subtotal = $line['qty'] * $line['net_unit_price'] - $line['discount'];
            return round($subtotal + $subtotal * ($line['tax_rate'] / 100), 2);
        })->toArray();
    }

    #[Computed]
    public function totals(): array
    {
        $subtotal = array_sum($this->lineTotals);
        $grandTotal = round(max($subtotal + $this->shipping_cost - $this->order_discount, 0), 2);

        return [
            'item_count' => count($this->cart),
            'subtotal' => $subtotal,
            'grand_total' => $grandTotal,
        ];
    }

    #[Computed]
    public function changeDue(): float
    {
        return max($this->tendered - $this->totals['grand_total'], 0);
    }

    public function openPayment(): void
    {
        if (empty($this->cart)) {
            $this->addError('cart', 'Add at least one product before checking out.');
            return;
        }

        $this->tendered = $this->totals['grand_total'];
        $this->showPayment = true;
    }

    public function checkout(SaleService $saleService)
    {
        $this->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'account_id' => ['required', 'exists:accounts,id'],
            'sale_date' => ['required', 'date'],
            'cart' => ['required', 'array', 'min:1'],
        ]);

        try {
            $sale = $saleService->checkout(
                warehouseId: $this->cashRegister->warehouse_id,
                customerId: $this->customer_id,
                billerId: $this->biller_id,
                cashRegisterId: $this->cashRegister->id,
                userId: auth()->id(),
                lines: $this->cart,
                payment: [
                    'account_id' => $this->account_id,
                    'paying_method' => $this->paying_method,
                    'amount' => min($this->tendered, $this->totals['grand_total']),
                    'tendered' => $this->tendered,
                ],
                orderDiscount: $this->order_discount,
                shippingCost: $this->shipping_cost,
                saleDate: $this->sale_date,
            );
        } catch (\RuntimeException $e) {
            $this->addError('cart', $e->getMessage());
            return;
        }

        $this->reset(['cart', 'order_discount', 'shipping_cost', 'tendered', 'showPayment']);
        $this->sale_date = now()->toDateString();

        session()->flash('success', "Sale #{$sale->reference_no} completed.");

        return redirect()->route('sales.show', $sale);
    }

    public function render()
    {
        return view('livewire.pos.terminal', [
            'customers' => Customer::active()->orderBy('name')->get(),
            'accounts' => Account::active()->orderBy('name')->get(),
        ]);
    }
}

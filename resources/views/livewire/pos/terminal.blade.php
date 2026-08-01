<div class="flex h-full">

    {{-- Left: product search + results grid --}}
    <div class="flex-1 overflow-y-auto border-r border-border p-4">
        <div class="relative mb-4">
            <input type="text" wire:model.live.debounce.200ms="productSearch" autofocus
                   placeholder="Scan barcode or search products..." class="field-input">
        </div>

        @error('cart') <p class="mb-3 rounded-md bg-red-50 px-3 py-2 text-sm text-status-unpaid">{{ $message }}</p> @enderror

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
            @forelse ($this->productResults as $product)
                <button type="button" wire:click="addProduct({{ $product->id }})"
                        class="card text-left hover:bg-surface-3">
                    <div class="font-medium text-sm">{{ $product->name }}</div>
                    <div class="mt-1 text-xs text-text-secondary">{{ $product->code }}</div>
                    <div class="mt-2 text-sm font-semibold">₹{{ number_format($product->price, 2) }}</div>
                </button>
            @empty
                @if (strlen($productSearch) > 0)
                    <div class="col-span-full py-8 text-center text-text-muted">No matching products.</div>
                @else
                    <div class="col-span-full py-8 text-center text-text-muted">Start typing to search products.</div>
                @endif
            @endforelse
        </div>
    </div>

    {{-- Right: cart --}}
    <div class="flex w-96 flex-col p-4">
        <div class="mb-3 flex items-center justify-between">
            <div class="text-sm text-text-secondary">Cart · {{ $this->totals['item_count'] }} item(s)</div>
            @if (count($cart))
                <button wire:click="clearCart" class="text-xs text-status-unpaid hover:underline">Clear</button>
            @endif
        </div>

        <div class="flex-1 overflow-y-auto">
            @forelse ($cart as $index => $line)
                <div wire:key="cart-{{ $index }}" class="mb-2 flex items-center justify-between border-b border-border pb-2 text-sm">
                    <div class="min-w-0 flex-1">
                        <div class="truncate">{{ $line['product_name'] }}</div>
                        <div class="text-xs text-text-muted">₹{{ number_format($line['net_unit_price'], 2) }} / {{ $line['sale_unit_code'] }}</div>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <button wire:click="decrementLine({{ $index }})" class="h-6 w-6 rounded border border-border text-xs">−</button>
                        <span class="w-6 text-center">{{ $line['qty'] }}</span>
                        <button wire:click="incrementLine({{ $index }})" class="h-6 w-6 rounded border border-border text-xs">+</button>
                    </div>
                    <div class="w-16 text-right font-medium">₹{{ number_format($this->lineTotals[$index] ?? 0, 2) }}</div>
                </div>
            @empty
                <div class="py-12 text-center text-sm text-text-muted">Cart is empty — search a product to add it.</div>
            @endforelse
        </div>

        <div class="mt-3 space-y-2 border-t border-border pt-3">
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="field-label">Customer</label>
                    <select wire:model="customer_id" class="field-input">
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="field-label">Sale date</label>
                    <input type="date" wire:model="sale_date" class="field-input">
                    @error('sale_date') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="field-label">Discount</label>
                    <input type="number" step="0.01" wire:model.live="order_discount" class="field-input">
                </div>
                <div>
                    <label class="field-label">Shipping</label>
                    <input type="number" step="0.01" wire:model.live="shipping_cost" class="field-input">
                </div>
            </div>

            <div class="flex justify-between border-t border-border pt-2 text-base font-semibold">
                <span>Total</span><span>₹{{ number_format($this->totals['grand_total'], 2) }}</span>
            </div>

            <button wire:click="openPayment" class="btn-primary w-full">Charge ₹{{ number_format($this->totals['grand_total'], 2) }}</button>
        </div>
    </div>

    {{-- Payment modal --}}
    @if ($showPayment)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 p-4">
            <div class="card w-full max-w-sm bg-surface-1 space-y-4">
                <div class="text-lg font-semibold">Take payment</div>
                <div class="text-sm text-text-secondary">Total due: <span class="font-medium text-text-primary">₹{{ number_format($this->totals['grand_total'], 2) }}</span></div>

                <div class="grid grid-cols-2 gap-2">
                    <button type="button" wire:click="$set('paying_method', 'Cash')"
                            class="{{ $paying_method === 'Cash' ? 'btn-primary' : 'btn-outline' }}">Cash</button>
                    <button type="button" wire:click="$set('paying_method', 'Card')"
                            class="{{ $paying_method === 'Card' ? 'btn-primary' : 'btn-outline' }}">Card</button>
                </div>

                <div>
                    <label class="field-label">Account</label>
                    <select wire:model="account_id" class="field-input">
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="field-label">Amount tendered</label>
                    <input type="number" step="0.01" wire:model.live="tendered" class="field-input">
                </div>

                @if ($this->changeDue > 0)
                    <div class="rounded-md bg-primary-50 px-3 py-2 text-sm text-primary-600">
                        Change due: ₹{{ number_format($this->changeDue, 2) }}
                    </div>
                @endif

                <div class="flex justify-end gap-2 border-t border-border pt-3">
                    <button type="button" class="btn-outline" wire:click="$set('showPayment', false)">Cancel</button>
                    <button wire:click="checkout" class="btn-primary" wire:loading.attr="disabled" wire:target="checkout">
                        <span wire:loading.remove wire:target="checkout">Complete sale</span>
                        <span wire:loading wire:target="checkout">Processing…</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

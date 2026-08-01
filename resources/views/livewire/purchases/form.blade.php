<div class="max-w-4xl">
    <form wire:submit="save" class="space-y-4">

        <div class="card grid grid-cols-4 gap-4">
            <div>
                <label class="field-label">Warehouse</label>
                <select wire:model="warehouse_id" class="field-input">
                    <option value="">Select warehouse</option>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
                @error('warehouse_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="field-label">Supplier</label>
                <select wire:model="supplier_id" class="field-input">
                    <option value="">Select supplier</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="field-label">Supplier invoice #</label>
                <input type="text" wire:model="invoice_number" class="field-input">
            </div>
            <div>
                <label class="field-label">Purchase date</label>
                <input type="date" wire:model="purchase_date" class="field-input">
                @error('purchase_date') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Product search + add --}}
        <div class="card">
            <label class="field-label">Add product</label>
            <div class="relative">
                <input type="text" wire:model.live.debounce.250ms="productSearch"
                       placeholder="Search by name or code..." class="field-input">

                @if (strlen($productSearch) >= 2)
                    <div class="absolute z-10 mt-1 w-full rounded-lg border border-border bg-surface-1 shadow-lg">
                        @forelse ($this->productResults as $product)
                            <button type="button" wire:click="addProduct({{ $product->id }})"
                                    class="flex w-full items-center justify-between px-3 py-2 text-left text-sm hover:bg-surface-3">
                                <span>{{ $product->name }} <span class="text-text-muted">({{ $product->code }})</span></span>
                                <span class="text-text-secondary">₹{{ number_format($product->cost, 2) }}</span>
                            </button>
                        @empty
                            <div class="px-3 py-2 text-sm text-text-muted">No matching products.</div>
                        @endforelse
                    </div>
                @endif
            </div>
            @error('lines') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        {{-- Line items --}}
        <div class="card overflow-x-auto">
            <table class="table-base">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="w-20">Qty</th>
                        <th class="w-28">Unit cost</th>
                        <th class="w-24">Discount</th>
                        <th class="w-20">Tax %</th>
                        <th class="text-right w-28">Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lines as $index => $line)
                        <tr wire:key="line-{{ $index }}">
                            <td class="font-medium">{{ $line['product_name'] }}</td>
                            <td><input type="number" step="0.01" wire:model.live="lines.{{ $index }}.qty" class="field-input"></td>
                            <td><input type="number" step="0.01" wire:model.live="lines.{{ $index }}.net_unit_cost" class="field-input"></td>
                            <td><input type="number" step="0.01" wire:model.live="lines.{{ $index }}.discount" class="field-input"></td>
                            <td><input type="number" step="0.01" wire:model.live="lines.{{ $index }}.tax_rate" class="field-input"></td>
                            <td class="text-right">₹{{ number_format($this->lineTotals[$index] ?? 0, 2) }}</td>
                            <td><button type="button" wire:click="removeLine({{ $index }})" class="text-xs text-status-unpaid hover:underline">Remove</button></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-6 text-center text-text-muted">No products added yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Order-level adjustments + totals --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="card space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="field-label">Order tax %</label>
                        <input type="number" step="0.01" wire:model.live="order_tax_rate" class="field-input">
                    </div>
                    <div>
                        <label class="field-label">Order discount</label>
                        <input type="number" step="0.01" wire:model.live="order_discount" class="field-input">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="field-label">Shipping cost</label>
                        <input type="number" step="0.01" wire:model.live="shipping_cost" class="field-input">
                    </div>
                    <div>
                        <label class="field-label">Paid amount</label>
                        <input type="number" step="0.01" wire:model="paid_amount" class="field-input">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="field-label">Status</label>
                        <select wire:model="status" class="field-input">
                            <option value="pending">Pending</option>
                            <option value="ordered">Ordered</option>
                            <option value="received">Received</option>
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Payment status</label>
                        <select wire:model="payment_status" class="field-input">
                            <option value="unpaid">Unpaid</option>
                            <option value="partial">Partial</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="field-label">Note</label>
                    <textarea wire:model="note" rows="2" class="field-input"></textarea>
                </div>
            </div>

            <div class="card h-fit space-y-2 text-sm">
                <div class="flex justify-between text-text-secondary"><span>Items</span><span>{{ $this->totals['item_count'] }}</span></div>
                <div class="flex justify-between text-text-secondary"><span>Total qty</span><span>{{ $this->totals['total_qty'] }}</span></div>
                <div class="flex justify-between text-text-secondary"><span>Subtotal</span><span>₹{{ number_format($this->totals['total_cost'], 2) }}</span></div>
                <div class="flex justify-between text-text-secondary"><span>Order tax</span><span>₹{{ number_format($this->totals['order_tax'], 2) }}</span></div>
                <div class="flex justify-between border-t border-border pt-2 text-base font-semibold"><span>Grand total</span><span>₹{{ number_format($this->totals['grand_total'], 2) }}</span></div>

                @if ($status === 'received' && $originalStatus !== 'received')
                    <p class="rounded-md bg-primary-50 px-2 py-1.5 text-xs text-primary-600">
                        Saving will add this purchase's quantities to stock in the selected warehouse.
                    </p>
                @endif
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('purchases.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">{{ $purchaseId ? 'Save changes' : 'Create purchase' }}</span>
                <span wire:loading wire:target="save">Saving…</span>
            </button>
        </div>
    </form>
</div>

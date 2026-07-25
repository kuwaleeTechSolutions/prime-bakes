<div class="max-w-3xl">
    <form wire:submit="save" class="space-y-4">

        <div class="card">
            <label class="field-label">Warehouse</label>
            <select wire:model.live="from_warehouse_id" class="field-input">
                <option value="">Select warehouse</option>
                @foreach ($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
            @error('from_warehouse_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <div class="card">
            <label class="field-label">Add damaged product</label>
            <div class="relative">
                <input type="text" wire:model.live.debounce.250ms="productSearch"
                       placeholder="{{ $from_warehouse_id ? 'Search by name or code...' : 'Select a warehouse first' }}"
                       class="field-input" @disabled(! $from_warehouse_id)>

                @if (strlen($productSearch) >= 2)
                    <div class="absolute z-10 mt-1 w-full rounded-lg border border-border bg-surface-1 shadow-lg">
                        @forelse ($this->productResults as $product)
                            <button type="button" wire:click="addProduct({{ $product->id }})"
                                    class="flex w-full items-center justify-between px-3 py-2 text-left text-sm hover:bg-surface-3">
                                {{ $product->name }} <span class="text-text-muted">({{ $product->code }})</span>
                            </button>
                        @empty
                            <div class="px-3 py-2 text-sm text-text-muted">No matching products.</div>
                        @endforelse
                    </div>
                @endif
            </div>
            @error('lines') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <div class="card overflow-x-auto">
            <table class="table-base">
                <thead><tr><th>Product</th><th class="w-24">Available</th><th class="w-20">Qty</th><th class="text-right w-28">Cost</th><th></th></tr></thead>
                <tbody>
                    @forelse ($lines as $index => $line)
                        <tr wire:key="dline-{{ $index }}">
                            <td class="font-medium">{{ $line['product_name'] }}</td>
                            <td class="text-text-secondary">{{ $line['available_qty'] }}</td>
                            <td><input type="number" step="0.01" max="{{ $line['available_qty'] }}" wire:model.live="lines.{{ $index }}.qty" class="field-input"></td>
                            <td class="text-right">₹{{ number_format($this->lineTotals[$index] ?? 0, 2) }}</td>
                            <td><button type="button" wire:click="removeLine({{ $index }})" class="text-xs text-status-unpaid hover:underline">Remove</button></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-6 text-center text-text-muted">No products added yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card space-y-3">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="field-label">Disposal cost (optional)</label>
                    <input type="number" step="0.01" wire:model.live="disposal_cost" class="field-input">
                </div>
                <div class="flex items-end justify-end text-sm font-semibold">
                    Grand total: ₹{{ number_format($this->grandTotal, 2) }}
                </div>
            </div>
            <div>
                <label class="field-label">Note</label>
                <textarea wire:model="note" rows="2" class="field-input" placeholder="e.g. broken in transit, expired, spoiled"></textarea>
            </div>
            <p class="rounded-md bg-red-50 px-2 py-1.5 text-xs text-status-unpaid">
                Saving permanently removes this quantity from stock in the selected warehouse.
            </p>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('damages.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">Log damage &amp; remove stock</span>
                <span wire:loading wire:target="save">Saving…</span>
            </button>
        </div>
    </form>
</div>

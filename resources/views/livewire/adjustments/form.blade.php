<div class="max-w-2xl">
    <form wire:submit="save" class="space-y-4">

        <div class="card">
            <label class="field-label">Warehouse</label>
            <select wire:model.live="warehouse_id" class="field-input">
                <option value="">Select warehouse</option>
                @foreach ($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
            @error('warehouse_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <div class="card">
            <label class="field-label">Add product</label>
            <div class="relative">
                <input type="text" wire:model.live.debounce.250ms="productSearch"
                       placeholder="{{ $warehouse_id ? 'Search by name or code...' : 'Select a warehouse first' }}"
                       class="field-input" @disabled(! $warehouse_id)>

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
                <thead><tr><th>Product</th><th class="w-24">Current</th><th class="w-28">Action</th><th class="w-20">Qty</th><th></th></tr></thead>
                <tbody>
                    @forelse ($lines as $index => $line)
                        <tr wire:key="aline-{{ $index }}">
                            <td class="font-medium">{{ $line['product_name'] }}</td>
                            <td class="text-text-secondary">{{ $line['current_qty'] }}</td>
                            <td>
                                <select wire:model="lines.{{ $index }}.action" class="field-input">
                                    <option value="+">Add (+)</option>
                                    <option value="-">Remove (−)</option>
                                </select>
                            </td>
                            <td><input type="number" step="0.01" wire:model="lines.{{ $index }}.qty" class="field-input"></td>
                            <td><button type="button" wire:click="removeLine({{ $index }})" class="text-xs text-status-unpaid hover:underline">Remove</button></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-6 text-center text-text-muted">No products added yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card">
            <label class="field-label">Note</label>
            <textarea wire:model="note" rows="2" class="field-input" placeholder="Reason for adjustment (e.g. physical count correction, spoilage)"></textarea>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('adjustments.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">Apply adjustment</span>
                <span wire:loading wire:target="save">Applying…</span>
            </button>
        </div>
    </form>
</div>

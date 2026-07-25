<div class="max-w-3xl">

    @if (! $started)
        <form wire:submit="startCount" class="card space-y-4">
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

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="field-label">Category (optional — narrows the count)</label>
                    <select wire:model="category_id" class="field-input">
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="field-label">Brand (optional)</label>
                    <select wire:model="brand_id" class="field-input">
                        <option value="">All brands</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-primary">Load count sheet</button>
        </form>
    @else
        <form wire:submit="save" class="space-y-4">
            <div class="card overflow-x-auto max-h-[60vh] overflow-y-auto">
                <table class="table-base">
                    <thead>
                        <tr><th>Product</th><th class="w-24 text-right">System qty</th><th class="w-28">Counted qty</th><th class="w-20 text-right">Diff</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($lines as $index => $line)
                            @php $diff = $line['counted_qty'] - $line['system_qty']; @endphp
                            <tr wire:key="scline-{{ $index }}">
                                <td>{{ $line['product_name'] }}</td>
                                <td class="text-right text-text-secondary">{{ $line['system_qty'] }}</td>
                                <td><input type="number" step="0.01" wire:model.live="lines.{{ $index }}.counted_qty" class="field-input"></td>
                                <td class="text-right {{ $diff == 0 ? 'text-text-muted' : ($diff > 0 ? 'text-primary-600' : 'text-status-unpaid') }}">
                                    {{ $diff > 0 ? '+' : '' }}{{ $diff }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card">
                <label class="field-label">Note</label>
                <textarea wire:model="note" rows="2" class="field-input"></textarea>
                <p class="mt-2 rounded-md bg-primary-50 px-2 py-1.5 text-xs text-primary-600">
                    Saving applies every non-zero difference to stock immediately via an adjustment.
                </p>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('stock-counts.index') }}" class="btn-outline">Cancel</a>
                <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save">Reconcile stock</span>
                    <span wire:loading wire:target="save">Reconciling…</span>
                </button>
            </div>
        </form>
    @endif
</div>

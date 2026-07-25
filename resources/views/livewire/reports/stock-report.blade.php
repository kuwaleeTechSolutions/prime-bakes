<div>
    <div class="mb-4 flex flex-wrap items-end gap-2">
        <div>
            <label class="field-label">Search</label>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Product name/code" class="field-input">
        </div>
        <div>
            <label class="field-label">Warehouse</label>
            <select wire:model.live="warehouseFilter" class="field-input">
                <option value="">All warehouses</option>
                @foreach ($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <label class="flex items-center gap-2 pb-2 text-sm">
            <input type="checkbox" wire:model.live="lowStockOnly"> Low stock only
        </label>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Product</th><th>Category</th><th class="text-right">Cost</th><th class="text-right">Price</th><th class="text-right">Stock</th><th class="text-right">Alert at</th></tr></thead>
            <tbody>
                @forelse ($products as $product)
                    @php $stock = $product->stockRows->sum('qty'); @endphp
                    <tr wire:key="stk-{{ $product->id }}">
                        <td class="font-medium">{{ $product->name }}</td>
                        <td>{{ $product->category?->name }}</td>
                        <td class="text-right">₹{{ number_format($product->cost, 2) }}</td>
                        <td class="text-right">₹{{ number_format($product->price, 2) }}</td>
                        <td class="text-right">
                            <span class="{{ $product->alert_quantity !== null && $stock <= $product->alert_quantity ? 'pill-unpaid' : '' }}">
                                {{ number_format($stock, 0) }}
                            </span>
                        </td>
                        <td class="text-right text-text-secondary">{{ $product->alert_quantity ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-8 text-center text-text-muted">No products match.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
</div>

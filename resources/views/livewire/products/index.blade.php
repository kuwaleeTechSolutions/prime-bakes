<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4 flex flex-wrap items-center gap-2">
        <input type="text" wire:model.live.debounce.300ms="search"
               placeholder="Search by name or code..." class="field-input w-64">

        <select wire:model.live="categoryFilter" class="field-input w-44">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="brandFilter" class="field-input w-44">
            <option value="">All brands</option>
            @foreach ($brands as $brand)
                <option value="{{ $brand->id }}">{{ $brand->title }}</option>
            @endforeach
        </select>

        <select wire:model.live="statusFilter" class="field-input w-36">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="all">All</option>
        </select>
        <div class="mb-4 flex gap-4 border-b border-border text-sm">
    <span class="border-b-2 border-primary-500 px-1 pb-2 font-medium text-primary-600">Products</span>
    <a href="{{ route('categories.index') }}" class="px-1 pb-2 text-text-secondary hover:text-text-primary">Categories</a>
    <a href="{{ route('brands.index') }}" class="px-1 pb-2 text-text-secondary hover:text-text-primary">Brands</a>
    <a href="{{ route('units.index') }}" class="px-1 pb-2 text-text-secondary hover:text-text-primary">Units</a>
</div>



        <a href="{{ route('products.create') }}" class="btn-primary ml-auto">+ Add product</a>
        
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th class="text-right">Cost</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Stock</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr wire:key="product-{{ $product->id }}">
                        <td class="text-text-secondary">{{ $product->code }}</td>
                        <td class="font-medium">{{ $product->name }}</td>
                        <td>{{ $product->category?->name ?? '—' }}</td>
                        <td>{{ $product->brand?->title ?? '—' }}</td>
                        <td class="text-right">₹{{ number_format($product->cost, 2) }}</td>
                        <td class="text-right">₹{{ number_format($product->price, 2) }}</td>
                        <td class="text-right">
                            <span class="{{ $product->is_low_stock ? 'pill-unpaid' : '' }}">
                                {{ number_format($product->total_stock, 0) }} {{ $product->saleUnit?->unit_code }}
                            </span>
                        </td>
                        <td>
                            <button wire:click="toggleActive({{ $product->id }})"
                                    class="{{ $product->is_active ? 'pill-paid' : 'pill-pending' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="text-right">
                            <a href="{{ route('products.edit', $product) }}" class="text-text-accent text-xs hover:underline">Edit</a>
                            <button wire:click="confirmDelete({{ $product->id }})" class="ml-3 text-xs text-status-unpaid hover:underline">Deactivate</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="py-8 text-center text-text-muted">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>

    {{-- Deactivate confirmation --}}
    @if ($confirmingDeleteId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30" wire:click.self="$set('confirmingDeleteId', null)">
            <div class="card w-full max-w-sm bg-surface-1">
                <div class="mb-2 font-medium">Deactivate this product?</div>
                <p class="mb-4 text-sm text-text-secondary">It will be hidden from POS and product lists but kept for historical sales/purchase records.</p>
                <div class="flex justify-end gap-2">
                    <button class="btn-outline" wire:click="$set('confirmingDeleteId', null)">Cancel</button>
                    <button class="btn-primary" wire:click="delete">Deactivate</button>
                </div>
            </div>
        </div>
    @endif
</div>

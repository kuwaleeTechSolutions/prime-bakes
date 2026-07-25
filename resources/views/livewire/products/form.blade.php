<div class="max-w-3xl">
    <form wire:submit="save" class="card space-y-5">

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="field-label">Product name</label>
                <input type="text" wire:model="name" class="field-input">
                @error('name') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="field-label">Code / SKU</label>
                <input type="text" wire:model="code" class="field-input">
                @error('code') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="field-label">Category</label>
                <select wire:model="category_id" class="field-input">
                    <option value="">Select category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="field-label">Brand</label>
                <select wire:model="brand_id" class="field-input">
                    <option value="">No brand</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="field-label">Base unit</label>
                <select wire:model="unit_id" class="field-input">
                    <option value="">Select unit</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                    @endforeach
                </select>
                @error('unit_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="field-label">Purchase unit</label>
                <select wire:model="purchase_unit_id" class="field-input">
                    <option value="">Select unit</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                    @endforeach
                </select>
                @error('purchase_unit_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="field-label">Sale unit</label>
                <select wire:model="sale_unit_id" class="field-input">
                    <option value="">Select unit</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                    @endforeach
                </select>
                @error('sale_unit_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="field-label">Cost price</label>
                <input type="number" step="0.01" wire:model="cost" class="field-input">
                @error('cost') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="field-label">Sale price</label>
                <input type="number" step="0.01" wire:model="price" class="field-input">
                @error('price') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="field-label">Low-stock alert at</label>
                <input type="number" step="0.01" wire:model="alert_quantity" class="field-input">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="field-label">Tax</label>
                <select wire:model="tax_id" class="field-input">
                    <option value="">No tax</option>
                    @foreach ($taxes as $tax)
                        <option value="{{ $tax->id }}">{{ $tax->name }} ({{ $tax->rate }}%)</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="field-label">Tax method</label>
                <select wire:model="tax_method" class="field-input">
                    <option value="1">Inclusive</option>
                    <option value="2">Exclusive</option>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-6 text-sm">
            <label class="flex items-center gap-2"><input type="checkbox" wire:model="is_batch"> Track batch/expiry</label>
            <label class="flex items-center gap-2"><input type="checkbox" wire:model="is_variant"> Has variants</label>
            <label class="flex items-center gap-2"><input type="checkbox" wire:model="is_active"> Active</label>
        </div>

        <div class="flex justify-end gap-2 border-t border-border pt-4">
            <a href="{{ route('products.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">{{ $productId ? 'Save changes' : 'Create product' }}</span>
                <span wire:loading wire:target="save">Saving…</span>
            </button>
        </div>
    </form>
</div>

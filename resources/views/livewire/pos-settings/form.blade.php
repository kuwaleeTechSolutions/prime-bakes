<div class="max-w-lg">
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <form wire:submit="save" class="card space-y-4">
        <div>
            <label class="field-label">Default walk-in customer</label>
            <select wire:model="customer_id" class="field-input">
                <option value="">Select customer</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
            @error('customer_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="field-label">Default warehouse</label>
            <select wire:model="warehouse_id" class="field-input">
                <option value="">Select warehouse</option>
                @foreach ($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
            @error('warehouse_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="field-label">Default biller</label>
            <select wire:model="biller_id" class="field-input">
                <option value="">Select biller</option>
                @foreach ($billers as $biller)
                    <option value="{{ $biller->id }}">{{ $biller->name }}</option>
                @endforeach
            </select>
            @error('biller_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="field-label">Products shown per POS page</label>
            <input type="number" wire:model="product_number" class="field-input">
            @error('product_number') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="keybord_active"> Show on-screen keyboard in POS</label>

        <div class="flex justify-end border-t border-border pt-3">
            <button type="submit" class="btn-primary">Save POS settings</button>
        </div>
    </form>
</div>

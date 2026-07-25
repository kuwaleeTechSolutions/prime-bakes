<div class="flex min-h-[70vh] items-center justify-center">
    <form wire:submit="open" class="card w-full max-w-sm space-y-4">
        <div>
            <div class="text-lg font-semibold">Open your register</div>
            <p class="text-sm text-text-secondary">Count the cash drawer and enter the opening amount to start your shift.</p>
        </div>

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
            <label class="field-label">Opening cash</label>
            <input type="number" step="0.01" wire:model="cash_in_hand" class="field-input">
            @error('cash_in_hand') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="btn-primary w-full">Open register &amp; start selling</button>
    </form>
</div>

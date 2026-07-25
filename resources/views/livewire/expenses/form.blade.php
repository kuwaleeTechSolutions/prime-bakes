<div class="max-w-lg">
    <form wire:submit="save" class="card space-y-4">
        <div>
            <label class="field-label">Category</label>
            <select wire:model="expense_category_id" class="field-input">
                <option value="">Select category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('expense_category_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
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
                <label class="field-label">Paid from account</label>
                <select wire:model="account_id" class="field-input">
                    <option value="">Select account</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                    @endforeach
                </select>
                @error('account_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="field-label">Amount</label>
            <input type="number" step="0.01" wire:model="amount" class="field-input">
            @error('amount') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="field-label">Note</label>
            <textarea wire:model="note" rows="2" class="field-input"></textarea>
        </div>

        <div class="flex justify-end gap-2 border-t border-border pt-3">
            <a href="{{ route('expenses.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary">Save expense</button>
        </div>
    </form>
</div>

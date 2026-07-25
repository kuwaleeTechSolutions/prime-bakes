<div class="max-w-lg">
    <form wire:submit="save" class="card space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="field-label">From account</label>
                <select wire:model="from_account_id" class="field-input">
                    <option value="">Select account</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }} (₹{{ number_format($account->total_balance, 2) }})</option>
                    @endforeach
                </select>
                @error('from_account_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="field-label">To account</label>
                <select wire:model="to_account_id" class="field-input">
                    <option value="">Select account</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                    @endforeach
                </select>
                @error('to_account_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="field-label">Amount</label>
            <input type="number" step="0.01" wire:model="amount" class="field-input">
            @error('amount') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="field-label">Note</label>
            <input type="text" wire:model="note" class="field-input">
        </div>

        <div class="flex justify-end gap-2 border-t border-border pt-3">
            <a href="{{ route('money-transfers.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary">Transfer funds</button>
        </div>
    </form>
</div>

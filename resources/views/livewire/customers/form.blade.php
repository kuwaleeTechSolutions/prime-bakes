<div class="max-w-2xl">
    <form wire:submit="save" class="card space-y-5">

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="field-label">Customer name</label>
                <input type="text" wire:model="name" class="field-input">
                @error('name') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="field-label">Customer group</label>
                <select wire:model="customer_group_id" class="field-input">
                    <option value="">Select group</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }} @if($group->percentage) ({{ $group->percentage }}% off) @endif</option>
                    @endforeach
                </select>
                @error('customer_group_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="field-label">Company name (optional)</label>
                <input type="text" wire:model="company_name" class="field-input">
            </div>
            <div>
                <label class="field-label">Tax number (optional)</label>
                <input type="text" wire:model="tax_no" class="field-input">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="field-label">Phone</label>
                <input type="text" wire:model="phone_number" class="field-input">
                @error('phone_number') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="field-label">Email (optional)</label>
                <input type="email" wire:model="email" class="field-input">
                @error('email') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="field-label">Address</label>
            <input type="text" wire:model="address" class="field-input">
            @error('address') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="field-label">City</label>
                <input type="text" wire:model="city" class="field-input">
            </div>
            <div>
                <label class="field-label">State</label>
                <input type="text" wire:model="state" class="field-input">
            </div>
            <div>
                <label class="field-label">Postal code</label>
                <input type="text" wire:model="postal_code" class="field-input">
            </div>
        </div>

        <div>
            <label class="field-label">Country</label>
            <input type="text" wire:model="country" class="field-input">
        </div>

        @if ($customerId)
            <div class="grid grid-cols-3 gap-4 rounded-lg border border-border bg-surface-2 p-3 text-sm">
                <div>
                    <div class="text-xs text-text-secondary">Loyalty points</div>
                    <div class="font-medium">{{ number_format($points, 0) }}</div>
                </div>
                <div>
                    <div class="text-xs text-text-secondary">Deposit balance</div>
                    <div class="font-medium">₹{{ number_format($deposit, 2) }}</div>
                </div>
                <div>
                    <div class="text-xs text-text-secondary">Total spent</div>
                    <div class="font-medium">₹{{ number_format($expense, 2) }}</div>
                </div>
            </div>
            <p class="text-xs text-text-muted -mt-3">These update automatically from sales and payments — not editable here.</p>
        @endif

        <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_active"> Active</label>

        <div class="flex justify-end gap-2 border-t border-border pt-4">
            <a href="{{ route('customers.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">{{ $customerId ? 'Save changes' : 'Create customer' }}</span>
                <span wire:loading wire:target="save">Saving…</span>
            </button>
        </div>
    </form>
</div>

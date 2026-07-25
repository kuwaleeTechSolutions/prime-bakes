<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex justify-end">
        <button wire:click="create" class="btn-primary">+ Issue gift card</button>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Card #</th><th>Customer</th><th class="text-right">Balance</th><th class="text-right">Redeemed</th><th>Expires</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($giftCards as $card)
                    <tr wire:key="gc-{{ $card->id }}">
                        <td class="font-medium">{{ $card->card_no }}</td>
                        <td>{{ $card->customer?->name ?? '—' }}</td>
                        <td class="text-right">₹{{ number_format($card->amount, 2) }}</td>
                        <td class="text-right">₹{{ number_format($card->expense, 2) }}</td>
                        <td class="{{ $card->isExpired() ? 'text-status-unpaid' : '' }}">{{ $card->expired_date?->format('d M Y') ?? 'No expiry' }}</td>
                        <td>
                            <button wire:click="toggleActive({{ $card->id }})" class="{{ $card->is_active ? 'pill-paid' : 'pill-pending' }}">
                                {{ $card->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="text-right"><button wire:click="openRecharge({{ $card->id }})" class="text-text-accent text-xs hover:underline">Recharge</button></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-8 text-center text-text-muted">No gift cards issued yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $giftCards->links() }}</div>

    @if ($showCreateForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30" wire:click.self="$set('showCreateForm', false)">
            <form wire:submit="save" class="card w-full max-w-sm bg-surface-1 space-y-4">
                <div class="font-medium">Issue gift card</div>
                <div>
                    <label class="field-label">Customer (optional)</label>
                    <select wire:model="customer_id" class="field-input">
                        <option value="">Unassigned</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="field-label">Initial amount</label>
                    <input type="number" step="0.01" wire:model="amount" class="field-input">
                    @error('amount') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Expiry date (optional)</label>
                    <input type="date" wire:model="expired_date" class="field-input">
                    @error('expired_date') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end gap-2 border-t border-border pt-3">
                    <button type="button" class="btn-outline" wire:click="$set('showCreateForm', false)">Cancel</button>
                    <button type="submit" class="btn-primary">Issue card</button>
                </div>
            </form>
        </div>
    @endif

    @if ($rechargingId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30" wire:click.self="$set('rechargingId', null)">
            <form wire:submit="recharge" class="card w-full max-w-sm bg-surface-1 space-y-4">
                <div class="font-medium">Recharge gift card</div>
                <div>
                    <label class="field-label">Amount to add</label>
                    <input type="number" step="0.01" wire:model="rechargeAmount" class="field-input">
                    @error('rechargeAmount') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end gap-2 border-t border-border pt-3">
                    <button type="button" class="btn-outline" wire:click="$set('rechargingId', null)">Cancel</button>
                    <button type="submit" class="btn-primary">Recharge</button>
                </div>
            </form>
        </div>
    @endif
</div>

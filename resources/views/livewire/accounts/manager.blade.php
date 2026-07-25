<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex justify-end">
        <button wire:click="create" class="btn-primary">+ Add account</button>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Name</th><th>Account #</th><th class="text-right">Balance</th><th>Default</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($accounts as $account)
                    <tr wire:key="acc-{{ $account->id }}">
                        <td class="font-medium">{{ $account->name }}</td>
                        <td class="text-text-secondary">{{ $account->account_no }}</td>
                        <td class="text-right">₹{{ number_format($account->total_balance, 2) }}</td>
                        <td>{{ $account->is_default ? '★' : '' }}</td>
                        <td><span class="{{ $account->is_active ? 'pill-paid' : 'pill-pending' }}">{{ $account->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="text-right">
                            <button wire:click="edit({{ $account->id }})" class="text-text-accent text-xs hover:underline">Edit</button>
                            <button wire:click="delete({{ $account->id }})" class="ml-3 text-xs text-status-unpaid hover:underline">Deactivate</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-8 text-center text-text-muted">No accounts yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 p-4" wire:click.self="$set('showForm', false)">
            <form wire:submit="save" class="card w-full max-w-sm bg-surface-1 space-y-4">
                <div class="font-medium">{{ $editingId ? 'Edit account' : 'Add account' }}</div>

                <div>
                    <label class="field-label">Name</label>
                    <input type="text" wire:model="name" class="field-input">
                    @error('name') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Account #</label>
                    <input type="text" wire:model="account_no" class="field-input">
                </div>
                <div>
                    <label class="field-label">{{ $editingId ? 'Initial balance' : 'Opening balance' }}</label>
                    <input type="number" step="0.01" wire:model="initial_balance" class="field-input" @disabled($editingId)>
                    @if ($editingId) <p class="mt-1 text-xs text-text-muted">Opening balance can't be changed after creation — use Money Transfers to adjust.</p> @endif
                </div>
                <div>
                    <label class="field-label">Note</label>
                    <input type="text" wire:model="note" class="field-input">
                </div>

                <div class="grid grid-cols-2 gap-2 text-sm">
                    <label class="flex items-center gap-2"><input type="checkbox" wire:model="is_default"> Default for POS</label>
                    <label class="flex items-center gap-2"><input type="checkbox" wire:model="is_upi"> UPI account</label>
                    <label class="flex items-center gap-2"><input type="checkbox" wire:model="is_card"> Card account</label>
                    <label class="flex items-center gap-2"><input type="checkbox" wire:model="is_cheque"> Cheque account</label>
                </div>

                <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_active"> Active</label>

                <div class="flex justify-end gap-2 border-t border-border pt-3">
                    <button type="button" class="btn-outline" wire:click="$set('showForm', false)">Cancel</button>
                    <button type="submit" class="btn-primary">Save</button>
                </div>
            </form>
        </div>
    @endif
</div>

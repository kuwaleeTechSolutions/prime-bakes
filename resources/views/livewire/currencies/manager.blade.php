<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex justify-end">
        <button wire:click="create" class="btn-primary">+ Add currency</button>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Name</th><th>Code</th><th>Exchange rate</th><th></th></tr></thead>
            <tbody>
                @forelse ($currencies as $currency)
                    <tr wire:key="cur-{{ $currency->id }}">
                        <td class="font-medium">{{ $currency->name }}</td>
                        <td>{{ $currency->code }}</td>
                        <td>{{ $currency->exchange_rate }}</td>
                        <td class="text-right">
                            <button wire:click="edit({{ $currency->id }})" class="text-text-accent text-xs hover:underline">Edit</button>
                            <button wire:click="delete({{ $currency->id }})" class="ml-3 text-xs text-status-unpaid hover:underline">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-8 text-center text-text-muted">No currencies yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30" wire:click.self="$set('showForm', false)">
            <form wire:submit="save" class="card w-full max-w-sm bg-surface-1 space-y-4">
                <div class="font-medium">{{ $editingId ? 'Edit currency' : 'Add currency' }}</div>
                <div>
                    <label class="field-label">Name</label>
                    <input type="text" wire:model="name" class="field-input">
                    @error('name') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Code</label>
                    <input type="text" wire:model="code" placeholder="INR" class="field-input">
                    @error('code') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Exchange rate (relative to base currency)</label>
                    <input type="number" step="0.0001" wire:model="exchange_rate" class="field-input">
                    @error('exchange_rate') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end gap-2 border-t border-border pt-3">
                    <button type="button" class="btn-outline" wire:click="$set('showForm', false)">Cancel</button>
                    <button type="submit" class="btn-primary">Save</button>
                </div>
            </form>
        </div>
    @endif
</div>

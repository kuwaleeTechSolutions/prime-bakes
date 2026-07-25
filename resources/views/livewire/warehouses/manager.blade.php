<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex justify-end">
        <button wire:click="create" class="btn-primary">+ Add warehouse</button>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Name</th><th>Phone</th><th>Email</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($warehouses as $warehouse)
                    <tr wire:key="wh-{{ $warehouse->id }}">
                        <td class="font-medium">{{ $warehouse->name }}</td>
                        <td>{{ $warehouse->phone ?? '—' }}</td>
                        <td>{{ $warehouse->email ?? '—' }}</td>
                        <td><span class="{{ $warehouse->is_active ? 'pill-paid' : 'pill-pending' }}">{{ $warehouse->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="text-right">
                            <button wire:click="edit({{ $warehouse->id }})" class="text-text-accent text-xs hover:underline">Edit</button>
                            <button wire:click="delete({{ $warehouse->id }})" class="ml-3 text-xs text-status-unpaid hover:underline">Deactivate</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-text-muted">No warehouses yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 p-4" wire:click.self="$set('showForm', false)">
            <form wire:submit="save" class="card w-full max-w-sm bg-surface-1 space-y-4">
                <div class="font-medium">{{ $editingId ? 'Edit warehouse' : 'Add warehouse' }}</div>
                <div>
                    <label class="field-label">Name</label>
                    <input type="text" wire:model="name" class="field-input">
                    @error('name') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Phone</label>
                    <input type="text" wire:model="phone" class="field-input">
                </div>
                <div>
                    <label class="field-label">Email</label>
                    <input type="email" wire:model="email" class="field-input">
                </div>
                <div>
                    <label class="field-label">Address</label>
                    <textarea wire:model="address" rows="2" class="field-input"></textarea>
                    @error('address') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
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

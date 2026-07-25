<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex justify-end">
        <button wire:click="create" class="btn-primary">+ Add role</button>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Name</th><th>Description</th><th>Users</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($roles as $role)
                    <tr wire:key="role-{{ $role->id }}">
                        <td class="font-medium">{{ $role->name }}</td>
                        <td class="text-text-secondary">{{ $role->description ?? '—' }}</td>
                        <td>{{ $role->users_count }}</td>
                        <td><span class="{{ $role->is_active ? 'pill-paid' : 'pill-pending' }}">{{ $role->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="text-right">
                            <button wire:click="edit({{ $role->id }})" class="text-text-accent text-xs hover:underline">Edit</button>
                            @if ($role->name !== 'Admin')
                                <button wire:click="delete({{ $role->id }})" class="ml-3 text-xs text-status-unpaid hover:underline">Deactivate</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-text-muted">No roles yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 p-4" wire:click.self="$set('showForm', false)">
            <form wire:submit="save" class="card w-full max-w-lg bg-surface-1 space-y-4 max-h-[85vh] overflow-y-auto">
                <div class="font-medium">{{ $editingId ? 'Edit role' : 'Add role' }}</div>

                <div>
                    <label class="field-label">Name</label>
                    <input type="text" wire:model="name" class="field-input">
                    @error('name') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Description</label>
                    <input type="text" wire:model="description" class="field-input">
                </div>

                <div>
                    <label class="field-label">Permissions</label>
                    <div class="max-h-64 space-y-3 overflow-y-auto rounded-lg border border-border p-3">
                        @forelse ($permissionGroups as $group => $permissions)
                            <div>
                                <div class="mb-1 text-xs font-medium capitalize text-text-secondary">{{ $group }}</div>
                                <div class="grid grid-cols-2 gap-1">
                                    @foreach ($permissions as $permission)
                                        <label class="flex items-center gap-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-text-muted">No permissions defined yet.</p>
                        @endforelse
                    </div>
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

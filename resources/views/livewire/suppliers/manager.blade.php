<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex items-center gap-2">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search suppliers..." class="field-input w-64">
        <button wire:click="create" class="btn-primary ml-auto">+ Add supplier</button>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Name</th><th>Company</th><th>Phone</th><th>City</th><th>Due</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($suppliers as $supplier)
                    <tr wire:key="supplier-{{ $supplier->id }}">
                        <td class="font-medium">{{ $supplier->name }}</td>
                        <td>{{ $supplier->company_name }}</td>
                        <td>{{ $supplier->phone_number }}</td>
                        <td>{{ $supplier->city }}</td>
                        <td class="{{ $supplier->outstanding_due > 0 ? 'text-status-unpaid' : '' }}">₹{{ number_format($supplier->outstanding_due, 2) }}</td>
                        <td><span class="{{ $supplier->is_active ? 'pill-paid' : 'pill-pending' }}">{{ $supplier->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="text-right">
                            <button wire:click="edit({{ $supplier->id }})" class="text-text-accent text-xs hover:underline">Edit</button>
                            <button wire:click="delete({{ $supplier->id }})" class="ml-3 text-xs text-status-unpaid hover:underline">Deactivate</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-8 text-center text-text-muted">No suppliers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $suppliers->links() }}</div>

    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 p-4" wire:click.self="$set('showForm', false)">
            <form wire:submit="save" class="card w-full max-w-lg bg-surface-1 space-y-4 max-h-[90vh] overflow-y-auto">
                <div class="font-medium">{{ $editingId ? 'Edit supplier' : 'Add supplier' }}</div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="field-label">Contact name</label>
                        <input type="text" wire:model="name" class="field-input">
                        @error('name') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="field-label">Company name</label>
                        <input type="text" wire:model="company_name" class="field-input">
                        @error('company_name') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="field-label">Email</label>
                        <input type="email" wire:model="email" class="field-input">
                        @error('email') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="field-label">Phone</label>
                        <input type="text" wire:model="phone_number" class="field-input">
                        @error('phone_number') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="field-label">Address</label>
                    <input type="text" wire:model="address" class="field-input">
                    @error('address') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="field-label">City</label>
                        <input type="text" wire:model="city" class="field-input">
                        @error('city') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
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

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="field-label">Country</label>
                        <input type="text" wire:model="country" class="field-input">
                    </div>
                    <div>
                        <label class="field-label">VAT / Tax number</label>
                        <input type="text" wire:model="vat_number" class="field-input">
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

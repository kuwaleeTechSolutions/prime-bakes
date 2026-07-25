<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex justify-end">
        <button wire:click="create" class="btn-primary">+ Add unit</button>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Code</th><th>Name</th><th>Conversion</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($units as $unit)
                    <tr wire:key="unit-{{ $unit->id }}">
                        <td class="font-medium">{{ $unit->unit_code }}</td>
                        <td>{{ $unit->unit_name }}</td>
                        <td class="text-text-secondary text-xs">
                            @if ($unit->base_unit && $unit->base_unit !== '0')
                                1 {{ $unit->unit_code }} {{ $unit->operator }} {{ $unit->operation_value }}
                            @else
                                Base unit
                            @endif
                        </td>
                        <td><span class="{{ $unit->is_active ? 'pill-paid' : 'pill-pending' }}">{{ $unit->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="text-right">
                            <button wire:click="edit({{ $unit->id }})" class="text-text-accent text-xs hover:underline">Edit</button>
                            <button wire:click="delete({{ $unit->id }})" class="ml-3 text-xs text-status-unpaid hover:underline">Deactivate</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-text-muted">No units yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30" wire:click.self="$set('showForm', false)">
            <form wire:submit="save" class="card w-full max-w-sm bg-surface-1 space-y-4">
                <div class="font-medium">{{ $editingId ? 'Edit unit' : 'Add unit' }}</div>
                <div>
                    <label class="field-label">Unit code</label>
                    <input type="text" wire:model="unit_code" placeholder="e.g. Pcs, Kg, Packet" class="field-input">
                    @error('unit_code') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Unit name</label>
                    <input type="text" wire:model="unit_name" class="field-input">
                    @error('unit_name') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="field-label">Operator</label>
                        <select wire:model="operator" class="field-input">
                            <option value="*">× (multiply)</option>
                            <option value="/">÷ (divide)</option>
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Value</label>
                        <input type="number" step="0.01" wire:model="operation_value" class="field-input">
                        @error('operation_value') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                    </div>
                </div>
                <p class="text-xs text-text-muted">e.g. "Packet" = base unit × 12, if 1 packet contains 12 base-unit pieces.</p>
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_active"> Active</label>
                <div class="flex justify-end gap-2 border-t border-border pt-3">
                    <button type="button" class="btn-outline" wire:click="$set('showForm', false)">Cancel</button>
                    <button type="submit" class="btn-primary">Save</button>
                </div>
            </form>
        </div>
    @endif
</div>

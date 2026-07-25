<div class="max-w-lg">
    <form wire:submit="save" class="card space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="field-label">Name</label>
                <input type="text" wire:model="name" class="field-input">
                @error('name') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="field-label">Phone</label>
                <input type="text" wire:model="phone" class="field-input">
                @error('phone') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="field-label">Email</label>
            <input type="email" wire:model="email" class="field-input">
            @error('email') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="field-label">Role</label>
                <select wire:model="role_id" class="field-input">
                    <option value="">Select role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="field-label">Warehouse <span class="text-text-muted">(blank = owner-level, all warehouses)</span></label>
                <select wire:model="warehouse_id" class="field-input">
                    <option value="">All warehouses</option>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="field-label">Biller identity (optional)</label>
            <select wire:model="biller_id" class="field-input">
                <option value="">None</option>
                @foreach ($billers as $biller)
                    <option value="{{ $biller->id }}">{{ $biller->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="field-label">{{ $userId ? 'New password (optional)' : 'Password' }}</label>
                <input type="password" wire:model="password" class="field-input">
                @error('password') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="field-label">Confirm password</label>
                <input type="password" wire:model="password_confirmation" class="field-input">
            </div>
        </div>

        <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_active"> Active</label>

        <div class="flex justify-end gap-2 border-t border-border pt-3">
            <a href="{{ route('users.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary">{{ $userId ? 'Save changes' : 'Create user' }}</button>
        </div>
    </form>
</div>

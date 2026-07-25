<div class="max-w-2xl">
    <form wire:submit="save" class="card space-y-5">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="field-label">Name</label>
                <input type="text" wire:model="name" class="field-input">
                @error('name') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="field-label">Department</label>
                <select wire:model="department_id" class="field-input">
                    <option value="">Select department</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
                @error('department_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
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
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="field-label">City</label>
                <input type="text" wire:model="city" class="field-input">
            </div>
            <div>
                <label class="field-label">Country</label>
                <input type="text" wire:model="country" class="field-input">
            </div>
        </div>

        <div>
            <label class="field-label">Monthly salary</label>
            <input type="number" step="0.01" wire:model="salary_amount" class="field-input">
            @error('salary_amount') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_active"> Active</label>

        <div class="flex justify-end gap-2 border-t border-border pt-4">
            <a href="{{ route('employees.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary">{{ $employeeId ? 'Save changes' : 'Add employee' }}</button>
        </div>
    </form>
</div>

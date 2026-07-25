<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex flex-wrap items-center gap-2">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search name/email..." class="field-input w-64">
        <select wire:model.live="departmentFilter" class="field-input w-44">
            <option value="">All departments</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
            @endforeach
        </select>
        <a href="{{ route('employees.create') }}" class="btn-primary ml-auto">+ Add employee</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Department</th><th class="text-right">Salary</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($employees as $employee)
                    <tr wire:key="emp-{{ $employee->id }}">
                        <td class="font-medium">{{ $employee->name }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->phone_number }}</td>
                        <td>{{ $employee->department?->name }}</td>
                        <td class="text-right">₹{{ number_format($employee->salary_amount, 2) }}</td>
                        <td>
                            <button wire:click="toggleActive({{ $employee->id }})" class="{{ $employee->is_active ? 'pill-paid' : 'pill-pending' }}">
                                {{ $employee->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="text-right"><a href="{{ route('employees.edit', $employee) }}" class="text-text-accent text-xs hover:underline">Edit</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-8 text-center text-text-muted">No employees yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $employees->links() }}</div>
</div>

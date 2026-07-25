<?php

namespace App\Livewire\Employees;

use App\Models\Department;
use App\Models\Employee;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public string $departmentFilter = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingDepartmentFilter() { $this->resetPage(); }

    public function toggleActive(int $employeeId): void
    {
        $employee = Employee::findOrFail($employeeId);
        $employee->update(['is_active' => ! $employee->is_active]);
    }

    public function render()
    {
        $employees = Employee::query()
            ->with('department')
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"))
            ->when($this->departmentFilter, fn ($q) => $q->where('department_id', $this->departmentFilter))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.employees.index', [
            'employees' => $employees,
            'departments' => Department::active()->orderBy('name')->get(),
        ]);
    }
}

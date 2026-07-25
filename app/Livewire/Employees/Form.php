<?php

namespace App\Livewire\Employees;

use App\Models\Department;
use App\Models\Employee;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Form extends Component
{
    #[Locked]
    public ?int $employeeId = null;

    public string $name = '';
    public string $email = '';
    public string $phone_number = '';
    public ?int $department_id = null;
    public string $address = '';
    public string $city = '';
    public string $country = '';
    public float $salary_amount = 0;
    public bool $is_active = true;

    public function mount(?Employee $employee = null): void
    {
        if ($employee?->exists) {
            $this->employeeId = $employee->id;
            $this->fill($employee->only([
                'name', 'email', 'phone_number', 'department_id', 'address',
                'city', 'country', 'salary_amount', 'is_active',
            ]));
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191'],
            'phone_number' => ['required', 'string', 'max:191'],
            'department_id' => ['required', 'exists:departments,id'],
            'address' => ['nullable', 'string', 'max:191'],
            'city' => ['nullable', 'string', 'max:191'],
            'country' => ['nullable', 'string', 'max:191'],
            'salary_amount' => ['required', 'numeric', 'min:0'],
        ]);

        $validated['is_active'] = $this->is_active;

        Employee::updateOrCreate(['id' => $this->employeeId], $validated);

        session()->flash('success', $this->employeeId ? 'Employee updated.' : 'Employee added.');

        return redirect()->route('employees.index');
    }

    public function render()
    {
        return view('livewire.employees.form', [
            'departments' => Department::active()->orderBy('name')->get(),
        ]);
    }
}

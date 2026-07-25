<?php

namespace App\Livewire\Departments;

use App\Models\Department;
use Livewire\Component;

class Manager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $name = '';
    public bool $is_active = true;

    public function create(): void
    {
        $this->reset(['editingId', 'name']);
        $this->is_active = true;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $department = Department::findOrFail($id);
        $this->editingId = $department->id;
        $this->name = $department->name;
        $this->is_active = $department->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate(['name' => ['required', 'string', 'max:191']]);

        Department::updateOrCreate(['id' => $this->editingId], [
            'name' => $this->name,
            'is_active' => $this->is_active,
        ]);

        $this->showForm = false;
        session()->flash('success', 'Department saved.');
    }

    public function delete(int $id): void
    {
        Department::whereKey($id)->update(['is_active' => false]);
    }

    public function render()
    {
        return view('livewire.departments.manager', [
            'departments' => Department::withCount('employees')->orderBy('name')->get(),
        ]);
    }
}

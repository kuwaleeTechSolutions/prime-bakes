<?php

namespace App\Livewire\CustomerGroups;

use App\Models\CustomerGroup;
use Livewire\Component;

class Manager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $name = '';
    public float $percentage = 0;
    public bool $is_active = true;

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'percentage']);
        $this->is_active = true;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $group = CustomerGroup::findOrFail($id);
        $this->editingId = $group->id;
        $this->name = $group->name;
        $this->percentage = $group->percentage;
        $this->is_active = $group->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:191'],
            'percentage' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        CustomerGroup::updateOrCreate(['id' => $this->editingId], [
            'name' => $this->name,
            'percentage' => $this->percentage,
            'is_active' => $this->is_active,
        ]);

        $this->showForm = false;
        session()->flash('success', 'Customer group saved.');
    }

    public function delete(int $id): void
    {
        CustomerGroup::whereKey($id)->update(['is_active' => false]);
    }

    public function render()
    {
        return view('livewire.customer-groups.manager', [
            'groups' => CustomerGroup::withCount('customers')->orderBy('name')->get(),
        ]);
    }
}

<?php

namespace App\Livewire\Warehouses;

use App\Models\Warehouse;
use Livewire\Component;

class Manager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $phone = '';
    public string $email = '';
    public string $address = '';
    public bool $is_active = true;

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'phone', 'email', 'address']);
        $this->is_active = true;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $warehouse = Warehouse::findOrFail($id);
        $this->editingId = $warehouse->id;
        $this->fill($warehouse->only(['name', 'phone', 'email', 'address', 'is_active']));
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:191'],
            'phone' => ['nullable', 'string', 'max:191'],
            'email' => ['nullable', 'email', 'max:191'],
            'address' => ['required', 'string'],
        ]);

        Warehouse::updateOrCreate(['id' => $this->editingId], [
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'email' => $this->email ?: null,
            'address' => $this->address,
            'is_active' => $this->is_active,
        ]);

        $this->showForm = false;
        session()->flash('success', 'Warehouse saved.');
    }

    public function delete(int $id): void
    {
        // Deactivate only — every module from Products onward has FKs into
        // warehouses (stock, sales, purchases, cash registers...), so a hard
        // delete would either fail on the FK or silently orphan data.
        Warehouse::whereKey($id)->update(['is_active' => false]);
    }

    public function render()
    {
        return view('livewire.warehouses.manager', [
            'warehouses' => Warehouse::orderBy('name')->get(),
        ]);
    }
}

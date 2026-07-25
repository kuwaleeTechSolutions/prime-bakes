<?php

namespace App\Livewire\Units;

use App\Models\Unit;
use Livewire\Component;

class Manager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $unit_code = '';
    public string $unit_name = '';
    public ?int $base_unit = null; // id of the base unit this converts to, blank = this IS a base unit
    public string $operator = '*';
    public ?float $operation_value = 1;
    public bool $is_active = true;

    public function create(): void
    {
        $this->reset(['editingId', 'unit_code', 'unit_name', 'base_unit']);
        $this->operator = '*';
        $this->operation_value = 1;
        $this->is_active = true;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $unit = Unit::findOrFail($id);
        $this->editingId = $unit->id;
        $this->unit_code = $unit->unit_code;
        $this->unit_name = $unit->unit_name;
        $this->base_unit = $unit->base_unit ?: null;
        $this->operator = $unit->operator ?? '*';
        $this->operation_value = $unit->operation_value;
        $this->is_active = $unit->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'unit_code' => ['required', 'string', 'max:191'],
            'unit_name' => ['required', 'string', 'max:191'],
            'operation_value' => ['required', 'numeric', 'min:0'],
        ]);

        Unit::updateOrCreate(['id' => $this->editingId], [
            'unit_code' => $this->unit_code,
            'unit_name' => $this->unit_name,
            'base_unit' => $this->base_unit ? (string) $this->base_unit : '0',
            'operator' => $this->operator,
            'operation_value' => $this->operation_value,
            'is_active' => $this->is_active,
        ]);

        $this->showForm = false;
        session()->flash('success', 'Unit saved.');
    }

    public function delete(int $id): void
    {
        Unit::whereKey($id)->update(['is_active' => false]);
    }

    public function render()
    {
        return view('livewire.units.manager', [
            'units' => Unit::orderBy('unit_name')->get(),
        ]);
    }
}

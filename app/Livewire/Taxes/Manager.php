<?php

namespace App\Livewire\Taxes;

use App\Models\Tax;
use Livewire\Component;

class Manager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $name = '';
    public float $rate = 0;
    public bool $is_active = true;

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'rate']);
        $this->is_active = true;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $tax = Tax::findOrFail($id);
        $this->editingId = $tax->id;
        $this->name = $tax->name;
        $this->rate = $tax->rate;
        $this->is_active = $tax->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:191'],
            'rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        Tax::updateOrCreate(['id' => $this->editingId], [
            'name' => $this->name,
            'rate' => $this->rate,
            'is_active' => $this->is_active,
        ]);

        $this->showForm = false;
        session()->flash('success', 'Tax saved.');
    }

    public function delete(int $id): void
    {
        Tax::whereKey($id)->update(['is_active' => false]);
    }

    public function render()
    {
        return view('livewire.taxes.manager', [
            'taxes' => Tax::orderBy('name')->get(),
        ]);
    }
}

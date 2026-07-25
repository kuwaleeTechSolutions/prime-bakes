<?php

namespace App\Livewire\Currencies;

use App\Models\Currency;
use Livewire\Component;

class Manager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $code = '';
    public float $exchange_rate = 1;

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'code']);
        $this->exchange_rate = 1;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $currency = Currency::findOrFail($id);
        $this->editingId = $currency->id;
        $this->name = $currency->name;
        $this->code = $currency->code;
        $this->exchange_rate = $currency->exchange_rate;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:191'],
            'code' => ['required', 'string', 'max:10'],
            'exchange_rate' => ['required', 'numeric', 'min:0'],
        ]);

        Currency::updateOrCreate(['id' => $this->editingId], [
            'name' => $this->name,
            'code' => $this->code,
            'exchange_rate' => $this->exchange_rate,
        ]);

        $this->showForm = false;
        session()->flash('success', 'Currency saved.');
    }

    public function delete(int $id): void
    {
        Currency::destroy($id);
    }

    public function render()
    {
        return view('livewire.currencies.manager', [
            'currencies' => Currency::orderBy('name')->get(),
        ]);
    }
}

<?php

namespace App\Livewire\Billers;

use App\Models\Biller;
use Livewire\Component;

class Manager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $company_name = '';
    public string $email = '';
    public string $phone_number = '';
    public string $address = '';
    public string $city = '';
    public string $state = '';
    public string $postal_code = '';
    public string $country = '';
    public string $vat_number = '';
    public bool $is_active = true;

    public function create(): void
    {
        $this->reset([
            'editingId', 'name', 'company_name', 'email', 'phone_number',
            'address', 'city', 'state', 'postal_code', 'country', 'vat_number',
        ]);
        $this->is_active = true;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $biller = Biller::findOrFail($id);
        $this->editingId = $biller->id;
        $this->fill($biller->only([
            'name', 'company_name', 'email', 'phone_number', 'address',
            'city', 'state', 'postal_code', 'country', 'vat_number', 'is_active',
        ]));
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:191'],
            'company_name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191'],
            'phone_number' => ['required', 'string', 'max:191'],
            'address' => ['required', 'string', 'max:191'],
            'city' => ['required', 'string', 'max:191'],
        ]);

        Biller::updateOrCreate(['id' => $this->editingId], [
            'name' => $this->name,
            'company_name' => $this->company_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state ?: null,
            'postal_code' => $this->postal_code ?: null,
            'country' => $this->country ?: null,
            'vat_number' => $this->vat_number ?: null,
            'is_active' => $this->is_active,
        ]);

        $this->showForm = false;
        session()->flash('success', 'Biller saved.');
    }

    public function delete(int $id): void
    {
        Biller::whereKey($id)->update(['is_active' => false]);
    }

    public function render()
    {
        return view('livewire.billers.manager', [
            'billers' => Biller::orderBy('name')->get(),
        ]);
    }
}

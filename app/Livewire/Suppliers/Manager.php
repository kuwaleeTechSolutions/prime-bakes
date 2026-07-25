<?php

namespace App\Livewire\Suppliers;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class Manager extends Component
{
    use WithPagination;

    public string $search = '';
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

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
        $supplier = Supplier::findOrFail($id);
        $this->editingId = $supplier->id;
        $this->fill($supplier->only([
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

        Supplier::updateOrCreate(['id' => $this->editingId], [
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
        session()->flash('success', 'Supplier saved.');
    }

    public function delete(int $id): void
    {
        Supplier::whereKey($id)->update(['is_active' => false]);
    }

    public function render()
    {
        return view('livewire.suppliers.manager', [
            'suppliers' => Supplier::query()
                ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('company_name', 'like', "%{$this->search}%"))
                ->orderBy('name')
                ->paginate(15),
        ]);
    }
}

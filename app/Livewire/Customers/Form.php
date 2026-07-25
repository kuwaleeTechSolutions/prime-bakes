<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use App\Models\CustomerGroup;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Form extends Component
{
    #[Locked]
    public ?int $customerId = null;

    public ?int $customer_group_id = null;
    public string $name = '';
    public string $company_name = '';
    public string $email = '';
    public string $phone_number = '';
    public string $tax_no = '';
    public string $address = '';
    public string $city = '';
    public string $state = '';
    public string $postal_code = '';
    public string $country = '';
    public bool $is_active = true;

    // Read-only in this form — points/deposit only change through Sales/Accounting
    // transactions (see Customer model helpers), never edited directly here.
    public float $points = 0;
    public float $deposit = 0;
    public float $expense = 0;

    public function mount(?Customer $customer = null): void
    {
        if ($customer?->exists) {
            $this->customerId = $customer->id;
            $this->fill($customer->only([
                'customer_group_id', 'name', 'company_name', 'email', 'phone_number',
                'tax_no', 'address', 'city', 'state', 'postal_code', 'country',
                'points', 'deposit', 'expense', 'is_active',
            ]));
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'customer_group_id' => ['required', 'exists:customer_groups,id'],
            'name' => ['required', 'string', 'max:191'],
            'company_name' => ['nullable', 'string', 'max:191'],
            'email' => ['nullable', 'email', 'max:191'],
            'phone_number' => ['required', 'string', 'max:191'],
            'tax_no' => ['nullable', 'string', 'max:191'],
            'address' => ['required', 'string', 'max:191'],
            'city' => ['nullable', 'string', 'max:191'],
            'state' => ['nullable', 'string', 'max:191'],
            'postal_code' => ['nullable', 'string', 'max:191'],
            'country' => ['nullable', 'string', 'max:191'],
        ]);

        $validated['is_active'] = $this->is_active;

        Customer::updateOrCreate(['id' => $this->customerId], $validated);

        session()->flash('success', $this->customerId ? 'Customer updated.' : 'Customer created.');

        return redirect()->route('customers.index');
    }

    public function render()
    {
        return view('livewire.customers.form', [
            'groups' => CustomerGroup::active()->orderBy('name')->get(),
        ]);
    }
}

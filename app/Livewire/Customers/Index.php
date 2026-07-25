<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use App\Models\CustomerGroup;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public string $groupFilter = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingGroupFilter() { $this->resetPage(); }

    public function toggleActive(int $customerId): void
    {
        $customer = Customer::findOrFail($customerId);
        $customer->update(['is_active' => ! $customer->is_active]);
    }

    public function render()
    {
        $customers = Customer::query()
            ->with('group')
            ->search($this->search)
            ->when($this->groupFilter, fn ($q) => $q->where('customer_group_id', $this->groupFilter))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.customers.index', [
            'customers' => $customers,
            'groups' => CustomerGroup::active()->orderBy('name')->get(),
        ]);
    }
}

<?php

namespace App\Livewire\Purchases;

use App\Models\Purchase;
use App\Models\Supplier;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public string $statusFilter = '';
    public string $paymentStatusFilter = '';
    public string $supplierFilter = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingPaymentStatusFilter() { $this->resetPage(); }
    public function updatingSupplierFilter() { $this->resetPage(); }

    public function render()
    {
        $purchases = Purchase::query()
            ->with(['supplier', 'warehouse'])
            ->when($this->search, fn ($q) => $q->where('reference_no', 'like', "%{$this->search}%")
                ->orWhere('invoice_number', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->paymentStatusFilter, fn ($q) => $q->where('payment_status', $this->paymentStatusFilter))
            ->when($this->supplierFilter, fn ($q) => $q->where('supplier_id', $this->supplierFilter))
            ->latest()
            ->paginate(15);

        return view('livewire.purchases.index', [
            'purchases' => $purchases,
            'suppliers' => Supplier::active()->orderBy('name')->get(),
        ]);
    }
}

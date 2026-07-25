<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
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

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingPaymentStatusFilter() { $this->resetPage(); }

    public function render()
    {
        $sales = Sale::query()
            ->with(['customer', 'warehouse', 'user'])
            ->when($this->search, fn ($q) => $q->where('reference_no', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('sale_status', $this->statusFilter))
            ->when($this->paymentStatusFilter, fn ($q) => $q->where('payment_status', $this->paymentStatusFilter))
            ->latest()
            ->paginate(15);

        return view('livewire.sales.index', compact('sales'));
    }
}

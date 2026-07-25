<?php

namespace App\Livewire\Transfers;

use App\Models\Transfer;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public string $statusFilter = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    public function render()
    {
        $transfers = Transfer::query()
            ->with(['fromWarehouse', 'toWarehouse'])
            ->when($this->search, fn ($q) => $q->where('reference_no', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(15);

        return view('livewire.transfers.index', compact('transfers'));
    }
}

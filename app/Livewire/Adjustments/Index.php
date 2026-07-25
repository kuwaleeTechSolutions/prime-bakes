<?php

namespace App\Livewire\Adjustments;

use App\Models\Adjustment;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public function updatingSearch() { $this->resetPage(); }

    public function render()
    {
        $adjustments = Adjustment::query()
            ->with('warehouse')
            ->when($this->search, fn ($q) => $q->where('reference_no', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(15);

        return view('livewire.adjustments.index', compact('adjustments'));
    }
}

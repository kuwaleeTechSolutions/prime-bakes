<?php

namespace App\Livewire\Damages;

use App\Models\Damage;
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
        $damages = Damage::query()
            ->with('fromWarehouse')
            ->when($this->search, fn ($q) => $q->where('reference_no', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(15);

        return view('livewire.damages.index', compact('damages'));
    }
}

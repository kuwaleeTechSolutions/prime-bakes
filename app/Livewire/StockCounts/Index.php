<?php

namespace App\Livewire\StockCounts;

use App\Models\StockCount;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.stock-counts.index', [
            'counts' => StockCount::with('warehouse')->latest()->paginate(15),
        ]);
    }
}

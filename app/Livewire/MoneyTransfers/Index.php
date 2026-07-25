<?php

namespace App\Livewire\MoneyTransfers;

use App\Models\MoneyTransfer;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.money-transfers.index', [
            'transfers' => MoneyTransfer::with(['fromAccount', 'toAccount'])->latest()->paginate(15),
        ]);
    }
}

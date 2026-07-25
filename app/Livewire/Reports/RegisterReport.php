<?php

namespace App\Livewire\Reports;

use App\Models\CashRegister;
use Livewire\Component;
use Livewire\WithPagination;

class RegisterReport extends Component
{
    use WithPagination;

    public function render()
    {
        $registers = CashRegister::with(['user', 'warehouse'])->latest()->paginate(20);

        return view('livewire.reports.register-report', compact('registers'));
    }
}

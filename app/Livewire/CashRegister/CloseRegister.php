<?php

namespace App\Livewire\CashRegister;

use App\Models\CashRegister;
use Livewire\Component;

class CloseRegister extends Component
{
    public CashRegister $cashRegister;
    public float $counted_cash = 0;

    public function mount(CashRegister $cashRegister): void
    {
        $this->cashRegister = $cashRegister;
        $this->counted_cash = $cashRegister->cash_in_hand + $cashRegister->cash_sales_total;
    }

    public function close()
    {
        $this->cashRegister->close();

        session()->forget('active_warehouse_id');

        session()->flash('success', 'Register closed.');

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.cash-register.close-register');
    }
}

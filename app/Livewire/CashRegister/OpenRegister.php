<?php

namespace App\Livewire\CashRegister;

use App\Models\CashRegister;
use App\Models\Warehouse;
use Livewire\Component;

class OpenRegister extends Component
{
    public ?int $warehouse_id = null;
    public float $cash_in_hand = 0;

    public function mount(): void
    {
        // Default to the user's assigned warehouse if they're staff-level (see auth module's isOwnerLevel()).
        $this->warehouse_id = auth()->user()->warehouse_id;
    }

    public function open()
    {
        $this->validate([
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'cash_in_hand' => ['required', 'numeric', 'min:0'],
        ]);

        CashRegister::openFor(auth()->user(), $this->warehouse_id, $this->cash_in_hand);

        session(['active_warehouse_id' => $this->warehouse_id]);

        return redirect()->route('pos.index');
    }

    public function render()
    {
        return view('livewire.cash-register.open-register', [
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}

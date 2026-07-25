<?php

namespace App\Livewire;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\ProductWarehouse;
use App\Models\CashRegister;
use Livewire\Attributes\On;
use Livewire\Component;

class DashboardStats extends Component
{
    public float $todaySales = 0;
    public float $todayPurchases = 0;
    public int $lowStockCount = 0;
    public float $cashInRegister = 0;

    // Refresh automatically every 30s so the dashboard stays live without a full reload.
    // Add wire:poll.30s to the root element in the view to enable.
    public function mount(): void
    {
        $this->refreshStats();
    }

    #[On('sale-completed')]
    public function refreshStats(): void
    {
        $this->todaySales = Sale::whereDate('created_at', today())->sum('grand_total');
        $this->todayPurchases = Purchase::whereDate('created_at', today())->sum('grand_total');
        $this->lowStockCount = ProductWarehouse::where('qty', '<=', 10)->count();

        $register = CashRegister::where('user_id', auth()->id())
            ->where('status', 1) // open
            ->latest()
            ->first();

        $this->cashInRegister = $register->cash_in_hand ?? 0;
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }
}

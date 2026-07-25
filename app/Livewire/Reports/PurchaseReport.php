<?php

namespace App\Livewire\Reports;

use App\Models\Purchase;
use App\Models\Warehouse;
use Livewire\Component;

class PurchaseReport extends Component
{
    public string $from_date;
    public string $to_date;
    public string $warehouseFilter = '';

    public function mount(): void
    {
        $this->from_date = now()->startOfMonth()->toDateString();
        $this->to_date = now()->toDateString();
    }

    protected function query()
    {
        return Purchase::query()
            ->whereDate('created_at', '>=', $this->from_date)
            ->whereDate('created_at', '<=', $this->to_date)
            ->when($this->warehouseFilter, fn ($q) => $q->where('warehouse_id', $this->warehouseFilter));
    }

    public function render()
    {
        $purchases = (clone $this->query())->with(['supplier', 'warehouse'])->latest()->paginate(20);

        $totals = (clone $this->query())->selectRaw('
            COUNT(*) as purchase_count,
            SUM(grand_total) as grand_total,
            SUM(paid_amount) as paid_amount
        ')->first();

        return view('livewire.reports.purchase-report', [
            'purchases' => $purchases,
            'totals' => $totals,
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}

<?php

namespace App\Livewire\Reports;

use App\Models\Sale;
use App\Models\Warehouse;
use Livewire\Component;

class SalesReport extends Component
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
        return Sale::query()
            ->whereDate('created_at', '>=', $this->from_date)
            ->whereDate('created_at', '<=', $this->to_date)
            ->when($this->warehouseFilter, fn ($q) => $q->where('warehouse_id', $this->warehouseFilter));
    }

    public function render()
    {
        $sales = (clone $this->query())->with(['customer', 'warehouse'])->latest()->paginate(20);

        $totals = (clone $this->query())->selectRaw('
            COUNT(*) as sale_count,
            SUM(grand_total) as grand_total,
            SUM(paid_amount) as paid_amount,
            SUM(total_discount) as total_discount,
            SUM(total_tax) as total_tax
        ')->first();

        return view('livewire.reports.sales-report', [
            'sales' => $sales,
            'totals' => $totals,
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}

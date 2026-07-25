<?php

namespace App\Livewire\Reports;

use App\Models\Expense;
use App\Models\Payroll;
use App\Models\ProductSale;
use App\Models\Purchase;
use App\Models\Sale;
use Livewire\Component;

class ProfitLoss extends Component
{
    public string $from_date;
    public string $to_date;

    public function mount(): void
    {
        $this->from_date = now()->startOfMonth()->toDateString();
        $this->to_date = now()->toDateString();
    }

    protected function inRange($query)
    {
        return $query->whereDate('created_at', '>=', $this->from_date)
            ->whereDate('created_at', '<=', $this->to_date);
    }

    public function render()
    {
        $revenue = $this->inRange(Sale::query())->sum('grand_total');

        // Cost of goods sold: sum of (qty * product's cost) across every sale
        // line in range — a simpler proxy than true FIFO/weighted-average
        // costing, which would need purchase-batch tracking to do precisely.
        $cogs = $this->inRange(ProductSale::query()->join('sales', 'sales.id', '=', 'product_sales.sale_id'))
            ->join('products', 'products.id', '=', 'product_sales.product_id')
            ->selectRaw('SUM(product_sales.qty * products.cost) as cogs')
            ->value('cogs') ?? 0;

        $purchases = $this->inRange(Purchase::query())->sum('grand_total');
        $expenses = $this->inRange(Expense::query())->sum('amount');
        $payroll = $this->inRange(Payroll::query())->sum('amount');

        $grossProfit = $revenue - $cogs;
        $netProfit = $grossProfit - $expenses - $payroll;

        return view('livewire.reports.profit-loss', compact(
            'revenue', 'cogs', 'purchases', 'expenses', 'payroll', 'grossProfit', 'netProfit'
        ));
    }
}

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

    // Still used for Expense/Payroll, which don't have their own dedicated
    // business-date field (unlike Sale.sale_date / Purchase.purchase_date) —
    // safe here since neither of those queries involves a join, so
    // 'created_at' is never ambiguous for them specifically.
    protected function inRange($query)
    {
        return $query->whereDate('created_at', '>=', $this->from_date)
            ->whereDate('created_at', '<=', $this->to_date);
    }

    public function render()
    {
        $revenue = Sale::query()
            ->whereDate('sale_date', '>=', $this->from_date)
            ->whereDate('sale_date', '<=', $this->to_date)
            ->sum('grand_total');

        // Cost of goods sold: sum of (qty * product's cost) across every sale
        // line in range — a simpler proxy than true FIFO/weighted-average
        // costing, which would need purchase-batch tracking to do precisely.
        //
        // Joins product_sales + sales + products, all three of which have
        // their own created_at column — so the date filter must be qualified
        // explicitly (here, sales.sale_date) rather than left ambiguous.
        $cogs = ProductSale::query()
            ->join('sales', 'sales.id', '=', 'product_sales.sale_id')
            ->join('products', 'products.id', '=', 'product_sales.product_id')
            ->whereDate('sales.sale_date', '>=', $this->from_date)
            ->whereDate('sales.sale_date', '<=', $this->to_date)
            ->selectRaw('SUM(product_sales.qty * products.cost) as cogs')
            ->value('cogs') ?? 0;

        $purchases = Purchase::query()
            ->whereDate('purchase_date', '>=', $this->from_date)
            ->whereDate('purchase_date', '<=', $this->to_date)
            ->sum('grand_total');

        $expenses = $this->inRange(Expense::query())->sum('amount');
        $payroll = $this->inRange(Payroll::query())->sum('amount');

        $grossProfit = $revenue - $cogs;
        $netProfit = $grossProfit - $expenses - $payroll;

        return view('livewire.reports.profit-loss', compact(
            'revenue', 'cogs', 'purchases', 'expenses', 'payroll', 'grossProfit', 'netProfit'
        ));
    }
}

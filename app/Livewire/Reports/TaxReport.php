<?php

namespace App\Livewire\Reports;

use App\Models\Purchase;
use App\Models\Sale;
use Livewire\Component;

class TaxReport extends Component
{
    public string $from_date;
    public string $to_date;

    public function mount(): void
    {
        $this->from_date = now()->startOfMonth()->toDateString();
        $this->to_date = now()->toDateString();
    }

    public function render()
    {
        $taxCollected = Sale::query()
            ->whereDate('sale_date', '>=', $this->from_date)
            ->whereDate('sale_date', '<=', $this->to_date)
            ->sum('total_tax');

        $taxPaid = Purchase::query()
            ->whereDate('purchase_date', '>=', $this->from_date)
            ->whereDate('purchase_date', '<=', $this->to_date)
            ->sum('total_tax');

        return view('livewire.reports.tax-report', [
            'taxCollected' => $taxCollected,
            'taxPaid' => $taxPaid,
            'netTax' => $taxCollected - $taxPaid,
        ]);
    }
}

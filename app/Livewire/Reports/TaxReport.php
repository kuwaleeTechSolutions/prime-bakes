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

    protected function inRange($query)
    {
        return $query->whereDate('created_at', '>=', $this->from_date)
            ->whereDate('created_at', '<=', $this->to_date);
    }

    public function render()
    {
        $taxCollected = $this->inRange(Sale::query())->sum('total_tax');
        $taxPaid = $this->inRange(Purchase::query())->sum('total_tax');

        return view('livewire.reports.tax-report', [
            'taxCollected' => $taxCollected,
            'taxPaid' => $taxPaid,
            'netTax' => $taxCollected - $taxPaid,
        ]);
    }
}

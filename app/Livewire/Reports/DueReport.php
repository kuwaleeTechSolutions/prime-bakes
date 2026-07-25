<?php

namespace App\Livewire\Reports;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\Supplier;
use Livewire\Component;

class DueReport extends Component
{
    public string $tab = 'customers';

    public function render()
    {
        // Built via a raw groupBy against Sale/Purchase rather than a
        // Customer::sales()/Supplier::purchases() relation, since the
        // Customer model (People module) predates the Sale model (Sales
        // module) and doesn't declare that relationship — this avoids
        // needing to patch an earlier package's model.
        $customerDues = Sale::query()
            ->selectRaw('customer_id, SUM(grand_total) as sales_total, SUM(paid_amount) as paid_total')
            ->groupBy('customer_id')
            ->havingRaw('SUM(grand_total) - SUM(paid_amount) > 0')
            ->get()
            ->map(function ($row) {
                $customer = Customer::find($row->customer_id);
                return (object) [
                    'name' => $customer?->name ?? 'Unknown',
                    'phone' => $customer?->phone_number,
                    'due' => $row->sales_total - $row->paid_total,
                ];
            })
            ->sortByDesc('due')
            ->values();

        $supplierDues = \App\Models\Purchase::query()
            ->selectRaw('supplier_id, SUM(grand_total) as purchases_total, SUM(paid_amount) as paid_total')
            ->whereNotNull('supplier_id')
            ->groupBy('supplier_id')
            ->havingRaw('SUM(grand_total) - SUM(paid_amount) > 0')
            ->get()
            ->map(function ($row) {
                $supplier = Supplier::find($row->supplier_id);
                return (object) [
                    'name' => $supplier?->name ?? 'Unknown',
                    'phone' => $supplier?->phone_number,
                    'due' => $row->purchases_total - $row->paid_total,
                ];
            })
            ->sortByDesc('due')
            ->values();

        return view('livewire.reports.due-report', compact('customerDues', 'supplierDues'));
    }
}

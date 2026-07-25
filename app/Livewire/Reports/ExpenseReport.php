<?php

namespace App\Livewire\Reports;

use App\Models\Expense;
use Livewire\Component;

class ExpenseReport extends Component
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
        $byCategory = Expense::query()
            ->whereDate('created_at', '>=', $this->from_date)
            ->whereDate('created_at', '<=', $this->to_date)
            ->with('category')
            ->get()
            ->groupBy(fn ($expense) => $expense->category?->name ?? 'Uncategorized')
            ->map(fn ($group) => $group->sum('amount'))
            ->sortDesc();

        $total = $byCategory->sum();

        return view('livewire.reports.expense-report', compact('byCategory', 'total'));
    }
}

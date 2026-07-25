<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public string $categoryFilter = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingCategoryFilter() { $this->resetPage(); }

    public function render()
    {
        $expenses = Expense::query()
            ->with(['category', 'account', 'warehouse'])
            ->when($this->search, fn ($q) => $q->where('reference_no', 'like', "%{$this->search}%")
                ->orWhere('note', 'like', "%{$this->search}%"))
            ->when($this->categoryFilter, fn ($q) => $q->where('expense_category_id', $this->categoryFilter))
            ->latest()
            ->paginate(15);

        return view('livewire.expenses.index', [
            'expenses' => $expenses,
            'categories' => ExpenseCategory::active()->orderBy('name')->get(),
        ]);
    }
}

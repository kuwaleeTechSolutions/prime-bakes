<?php

namespace App\Livewire\Expenses;

use App\Models\Account;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Form extends Component
{
    public ?int $expense_category_id = null;
    public ?int $warehouse_id = null;
    public ?int $account_id = null;
    public float $amount = 0;
    public string $note = '';

    public function save()
    {
        $this->validate([
            'expense_category_id' => ['required', 'exists:expense_categories,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'account_id' => ['required', 'exists:accounts,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        DB::transaction(function () {
            Expense::create([
                'reference_no' => Expense::generateReferenceNo(),
                'expense_category_id' => $this->expense_category_id,
                'warehouse_id' => $this->warehouse_id,
                'account_id' => $this->account_id,
                'user_id' => auth()->id(),
                'amount' => $this->amount,
                'note' => $this->note ?: null,
            ]);

            // Expenses reduce the paying account's balance — same debit()
            // helper Accounting screens should use consistently.
            Account::find($this->account_id)->debit($this->amount);
        });

        session()->flash('success', 'Expense recorded.');

        return redirect()->route('expenses.index');
    }

    public function render()
    {
        return view('livewire.expenses.form', [
            'categories' => ExpenseCategory::active()->orderBy('name')->get(),
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
            'accounts' => Account::active()->orderBy('name')->get(),
        ]);
    }
}

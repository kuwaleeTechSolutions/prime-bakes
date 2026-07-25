<?php

namespace App\Livewire\Payroll;

use App\Models\Account;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public bool $showForm = false;
    public ?int $employee_id = null;
    public ?int $account_id = null;
    public float $amount = 0;
    public string $paying_method = 'Bank transfer';
    public string $month = '';
    public string $note = '';

    public function create(): void
    {
        $this->reset(['employee_id', 'account_id', 'amount', 'note']);
        $this->month = now()->format('F');
        $this->showForm = true;
    }

    public function updatedEmployeeId(): void
    {
        // Pre-fill the amount with the employee's on-file salary — still editable.
        $this->amount = Employee::find($this->employee_id)?->salary_amount ?? 0;
    }

    public function save(): void
    {
        $this->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'account_id' => ['required', 'exists:accounts,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'month' => ['required', 'string', 'max:50'],
        ]);

        $employee = Employee::findOrFail($this->employee_id);

        if ($employee->paidFor($this->month)) {
            $this->addError('month', "{$employee->name} has already been paid for {$this->month}.");
            return;
        }

        DB::transaction(function () {
            Payroll::create([
                'reference_no' => Payroll::generateReferenceNo(),
                'employee_id' => $this->employee_id,
                'account_id' => $this->account_id,
                'user_id' => auth()->id(),
                'amount' => $this->amount,
                'paying_method' => $this->paying_method,
                'note' => $this->note ?: null,
                'month' => $this->month,
            ]);

            Account::find($this->account_id)->debit($this->amount);
        });

        $this->showForm = false;
        session()->flash('success', 'Salary paid.');
    }

    public function render()
    {
        return view('livewire.payroll.index', [
            'payrolls' => Payroll::with(['employee', 'account'])->latest()->paginate(15),
            'employees' => Employee::active()->orderBy('name')->get(),
            'accounts' => Account::active()->orderBy('name')->get(),
        ]);
    }
}

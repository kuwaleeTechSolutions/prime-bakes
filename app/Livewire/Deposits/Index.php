<?php

namespace App\Livewire\Deposits;

use App\Models\Customer;
use App\Models\Deposit;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public bool $showForm = false;
    public ?int $customer_id = null;
    public float $amount = 0;
    public string $note = '';

    public function create(): void
    {
        $this->reset(['customer_id', 'amount', 'note']);
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        Deposit::create([
            'amount' => $this->amount,
            'customer_id' => $this->customer_id,
            'user_id' => auth()->id(),
            'note' => $this->note ?: null,
        ]);

        // The Deposit row is the ledger entry; Customer::deposit is the running
        // balance — same addDeposit() helper built in the People module.
        Customer::find($this->customer_id)->addDeposit($this->amount);

        $this->showForm = false;
        session()->flash('success', 'Deposit recorded.');
    }

    public function render()
    {
        return view('livewire.deposits.index', [
            'deposits' => Deposit::with('customer')->latest()->paginate(15),
            'customers' => Customer::active()->orderBy('name')->get(),
        ]);
    }
}

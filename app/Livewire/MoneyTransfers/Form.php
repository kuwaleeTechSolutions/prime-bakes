<?php

namespace App\Livewire\MoneyTransfers;

use App\Models\Account;
use App\Models\MoneyTransfer;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Form extends Component
{
    public ?int $from_account_id = null;
    public ?int $to_account_id = null;
    public float $amount = 0;
    public string $note = '';

    public function save()
    {
        $this->validate([
            'from_account_id' => ['required', 'exists:accounts,id', 'different:to_account_id'],
            'to_account_id' => ['required', 'exists:accounts,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ], [
            'from_account_id.different' => 'Source and destination accounts must be different.',
        ]);

        $fromAccount = Account::findOrFail($this->from_account_id);

        if ($fromAccount->total_balance < $this->amount) {
            $this->addError('amount', "Insufficient balance in {$fromAccount->name} (available ₹" . number_format($fromAccount->total_balance, 2) . ').');
            return;
        }

        DB::transaction(function () use ($fromAccount) {
            MoneyTransfer::create([
                'reference_no' => MoneyTransfer::generateReferenceNo(),
                'from_account_id' => $this->from_account_id,
                'to_account_id' => $this->to_account_id,
                'amount' => $this->amount,
                'note' => $this->note ?: null,
            ]);

            $fromAccount->debit($this->amount);
            Account::find($this->to_account_id)->credit($this->amount);
        });

        session()->flash('success', 'Transfer completed.');

        return redirect()->route('money-transfers.index');
    }

    public function render()
    {
        return view('livewire.money-transfers.form', [
            'accounts' => Account::active()->orderBy('name')->get(),
        ]);
    }
}

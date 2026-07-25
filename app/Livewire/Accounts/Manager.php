<?php

namespace App\Livewire\Accounts;

use App\Models\Account;
use Livewire\Component;

class Manager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $account_no = '';
    public string $name = '';
    public float $initial_balance = 0;
    public string $note = '';
    public bool $is_default = false;
    public bool $is_upi = false;
    public bool $is_card = false;
    public bool $is_cheque = false;
    public bool $is_active = true;

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'note', 'is_default', 'is_upi', 'is_card', 'is_cheque']);
        $this->account_no = (string) (Account::max('id') + 1);
        $this->initial_balance = 0;
        $this->is_active = true;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $account = Account::findOrFail($id);
        $this->editingId = $account->id;
        $this->fill($account->only(['account_no', 'name', 'initial_balance', 'note', 'is_default', 'is_upi', 'is_card', 'is_cheque', 'is_active']));
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'account_no' => ['required', 'string', 'max:191'],
            'name' => ['required', 'string', 'max:191'],
            'initial_balance' => ['required', 'numeric'],
        ]);

        // If this account is being set as default, unset every other default first —
        // only one account should be the POS payment default at a time.
        if ($this->is_default) {
            Account::where('is_default', true)->update(['is_default' => false]);
        }

        $account = Account::updateOrCreate(['id' => $this->editingId], [
            'account_no' => $this->account_no,
            'name' => $this->name,
            'initial_balance' => $this->initial_balance,
            'note' => $this->note ?: null,
            'is_default' => $this->is_default,
            'is_upi' => $this->is_upi,
            'is_card' => $this->is_card,
            'is_cheque' => $this->is_cheque,
            'is_active' => $this->is_active,
        ]);

        if (! $this->editingId) {
            $account->update(['total_balance' => $this->initial_balance]);
        }

        $this->showForm = false;
        session()->flash('success', 'Account saved.');
    }

    public function delete(int $id): void
    {
        Account::whereKey($id)->update(['is_active' => false]);
    }

    public function render()
    {
        return view('livewire.accounts.manager', [
            'accounts' => Account::orderBy('name')->get(),
        ]);
    }
}

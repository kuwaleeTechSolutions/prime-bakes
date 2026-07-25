<?php

namespace App\Livewire\GiftCards;

use App\Models\Customer;
use App\Models\GiftCard;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public bool $showCreateForm = false;
    public ?int $rechargingId = null;

    public ?int $customer_id = null;
    public float $amount = 0;
    public string $expired_date = '';

    public float $rechargeAmount = 0;

    public function create(): void
    {
        $this->reset(['customer_id', 'amount', 'expired_date']);
        $this->showCreateForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'expired_date' => ['nullable', 'date', 'after:today'],
        ]);

        GiftCard::create([
            'card_no' => GiftCard::generateCardNo(),
            'amount' => $this->amount,
            'expense' => 0,
            'customer_id' => $this->customer_id,
            'expired_date' => $this->expired_date ?: null,
            'created_by' => auth()->id(),
            'is_active' => true,
        ]);

        $this->showCreateForm = false;
        session()->flash('success', 'Gift card issued.');
    }

    public function openRecharge(int $id): void
    {
        $this->rechargingId = $id;
        $this->rechargeAmount = 0;
    }

    public function recharge(): void
    {
        $this->validate(['rechargeAmount' => ['required', 'numeric', 'min:0.01']]);

        GiftCard::findOrFail($this->rechargingId)->recharge($this->rechargeAmount, auth()->id());

        $this->rechargingId = null;
        session()->flash('success', 'Gift card recharged.');
    }

    public function toggleActive(int $id): void
    {
        $card = GiftCard::findOrFail($id);
        $card->update(['is_active' => ! $card->is_active]);
    }

    public function render()
    {
        return view('livewire.gift-cards.index', [
            'giftCards' => GiftCard::with('customer')->latest()->paginate(15),
            'customers' => Customer::active()->orderBy('name')->get(),
        ]);
    }
}

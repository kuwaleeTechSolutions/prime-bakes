<?php

namespace App\Livewire\PosSettings;

use App\Models\Biller;
use App\Models\Customer;
use App\Models\PosSetting;
use App\Models\Warehouse;
use Livewire\Component;

class Form extends Component
{
    public ?int $customer_id = null;
    public ?int $warehouse_id = null;
    public ?int $biller_id = null;
    public int $product_number = 50;
    public bool $keybord_active = false;

    public function mount(): void
    {
        $settings = PosSetting::first();

        if ($settings) {
            $this->fill($settings->only(['customer_id', 'warehouse_id', 'biller_id', 'product_number', 'keybord_active']));
        }
    }

    public function save(): void
    {
        $this->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'biller_id' => ['required', 'exists:billers,id'],
            'product_number' => ['required', 'integer', 'min:10', 'max:200'],
        ]);

        PosSetting::updateOrCreate(['id' => 1], [
            'customer_id' => $this->customer_id,
            'warehouse_id' => $this->warehouse_id,
            'biller_id' => $this->biller_id,
            'product_number' => $this->product_number,
            'keybord_active' => $this->keybord_active,
        ]);

        session()->flash('success', 'POS settings saved.');
    }

    public function render()
    {
        return view('livewire.pos-settings.form', [
            'customers' => Customer::active()->orderBy('name')->get(),
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
            'billers' => Biller::active()->orderBy('name')->get(),
        ]);
    }
}

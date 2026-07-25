<?php

namespace App\Livewire\GeneralSettings;

use App\Models\GeneralSetting;
use Livewire\Component;

class Form extends Component
{
    public string $site_title = '';
    public bool $is_rtl = false;
    public string $currency = 'INR';
    public string $currency_position = 'prefix';
    public string $staff_access = 'own_warehouse';
    public string $date_format = 'd-m-Y';
    public string $invoice_format = 'standard';
    public bool $cash_register = true;

    public function mount(): void
    {
        $settings = GeneralSetting::current();
        $this->fill($settings->only([
            'site_title', 'is_rtl', 'currency', 'currency_position',
            'staff_access', 'date_format', 'invoice_format', 'cash_register',
        ]));
    }

    public function save(): void
    {
        $this->validate([
            'site_title' => ['required', 'string', 'max:191'],
            'currency' => ['required', 'string', 'max:10'],
        ]);

        GeneralSetting::current()->update([
            'site_title' => $this->site_title,
            'is_rtl' => $this->is_rtl,
            'currency' => $this->currency,
            'currency_position' => $this->currency_position,
            'staff_access' => $this->staff_access,
            'date_format' => $this->date_format,
            'invoice_format' => $this->invoice_format,
            'cash_register' => $this->cash_register,
        ]);

        session()->flash('success', 'Settings saved.');
    }

    public function render()
    {
        return view('livewire.general-settings.form');
    }
}

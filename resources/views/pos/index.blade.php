@php
    $openRegister = \App\Models\CashRegister::where('user_id', auth()->id())->where('status', true)->latest()->first();
@endphp

<x-layouts.pos :cash-register="$openRegister">
    @if ($openRegister)
        @livewire('pos.terminal', ['cashRegister' => $openRegister])
    @else
        @livewire('cash-register.open-register')
    @endif
</x-layouts.pos>
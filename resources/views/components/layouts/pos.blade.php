<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>POS · {{ config('app.name', 'Retailo POS') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full">

    <div class="flex h-screen flex-col overflow-hidden bg-surface-2">

        <header class="flex items-center justify-between border-b border-border bg-surface-1 px-4 py-2.5">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.5 12 4l9 5.5M4 10v9a1 1 0 0 0 1 1h4v-6h6v6h4a1 1 0 0 0 1-1v-9" />
                    </svg>
                    <span class="text-sm font-semibold">{{ config('app.name', 'Retailo POS') }}</span>
                </a>
                <span class="rounded-md border border-border px-2 py-0.5 text-xs text-text-secondary">{{ session('active_warehouse', 'POS') }}</span>
            </div>

            <div class="flex items-center gap-3 text-xs text-text-secondary">
                <span>{{ auth()->user()->name ?? '' }}</span>
                @if (isset($cashRegister))
                    <a href="{{ route('cash-register.close', $cashRegister) }}" class="rounded-md border border-border px-2 py-1 hover:bg-surface-3">Close register</a>
                @endif
                <a href="{{ route('dashboard') }}" class="rounded-md border border-border px-2 py-1 hover:bg-surface-3">Exit POS</a>
            </div>
        </header>

        <main class="flex-1 overflow-hidden">
            {{ $slot }}
        </main>

    </div>

    @livewireScripts
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Retailo POS') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">

    <div class="flex min-h-screen">

        {{-- Brand panel --}}
        <div class="hidden w-1/2 flex-col justify-between bg-primary-500 p-10 text-white lg:flex">
            <div class="flex items-center gap-2">
                {{-- swap for your own <img src="{{ asset('logo.svg') }}"> --}}
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.5 12 4l9 5.5M4 10v9a1 1 0 0 0 1 1h4v-6h6v6h4a1 1 0 0 0 1-1v-9" />
                </svg>
                <span class="text-base font-medium">{{ config('app.name', 'Retailo POS') }}</span>
            </div>

            <div class="max-w-xs">
                <h1 class="mb-2 text-2xl font-semibold leading-snug">
                    Run your whole store from one screen
                </h1>
                <p class="text-sm text-primary-50/90">
                    Sales, inventory, purchases and payroll, kept in sync across every warehouse.
                </p>
            </div>

            <div class="flex gap-1.5">
                <span class="h-1 w-5 rounded-full bg-white/90"></span>
                <span class="h-1 w-1.5 rounded-full bg-white/30"></span>
                <span class="h-1 w-1.5 rounded-full bg-white/30"></span>
            </div>
        </div>

        {{-- Form panel --}}
        <div class="flex w-full flex-col justify-center bg-surface-2 px-6 py-12 lg:w-1/2">
            <div class="mx-auto w-full max-w-sm">
                {{ $slot }}
            </div>
        </div>

    </div>

    @livewireScripts
</body>
</html>

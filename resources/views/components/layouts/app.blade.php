<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($header) ? $header . ' · ' : '' }}{{ config('app.name', 'Retailo POS') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full">

    <div class="flex h-screen overflow-hidden bg-surface-2">

        @include('layouts.partials.sidebar')

        <div class="flex flex-1 flex-col overflow-hidden">
            @include('layouts.partials.header')

            <main class="flex-1 overflow-y-auto p-5">
                {{ $slot }}
            </main>

            @include('layouts.partials.footer')
        </div>

    </div>

    @livewireScripts
</body>
</html>

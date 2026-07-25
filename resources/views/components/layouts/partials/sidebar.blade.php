@php
    // name => [icon path (heroicons-style), route name, badge]
    $nav = [
        ['label' => 'Dashboard',  'route' => 'dashboard',       'icon' => 'grid'],
        ['label' => 'POS',        'route' => 'pos.index',       'icon' => 'cart'],
        ['label' => 'Sales',      'route' => 'sales.index',     'icon' => 'receipt'],
        ['label' => 'Purchases',  'route' => 'purchases.index', 'icon' => 'truck'],
        ['label' => 'Products',   'route' => 'products.index',  'icon' => 'box'],
        ['label' => 'Stock',      'route' => 'stock.index',     'icon' => 'layers'],
        ['label' => 'People',     'route' => 'people.index',    'icon' => 'users'],
        ['label' => 'Accounting', 'route' => 'accounting.index','icon' => 'cash'],
        ['label' => 'HRM',        'route' => 'hrm.index',       'icon' => 'badge'],
        ['label' => 'Reports',    'route' => 'reports.index',   'icon' => 'chart'],
        ['label' => 'Settings',   'route' => 'settings.index',  'icon' => 'cog'],
    ];

    // Minimal inline icon paths so this file has zero extra dependencies.
    // Swap for an icon package (e.g. blade-ui-kit/blade-heroicons) if you prefer.
    $icons = [
        'grid'    => 'M4 4h7v7H4V4Zm9 0h7v7h-7V4ZM4 13h7v7H4v-7Zm9 0h7v7h-7v-7Z',
        'cart'    => 'M3 3h2l2.6 12.4A2 2 0 0 0 9.55 17H18a2 2 0 0 0 1.94-1.51L21.5 8H6',
        'receipt' => 'M6 3h12v18l-3-2-3 2-3-2-3 2V3Z',
        'truck'   => 'M3 7h11v8H3V7Zm11 3h4l3 3v2h-7v-5ZM6.5 20a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm12 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z',
        'box'     => 'M21 8 12 3 3 8l9 5 9-5Zm0 0v8l-9 5-9-5V8m18 0-9 5m0 0L3 8',
        'layers'  => 'm12 3 9 5-9 5-9-5 9-5Zm-9 9 9 5 9-5M3 16l9 5 9-5',
        'users'   => 'M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2M11 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm10 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75',
        'cash'    => 'M2 7h20v10H2V7Zm10 2.5A2.5 2.5 0 1 1 12 15a2.5 2.5 0 0 1 0-5.5ZM5 9v0M19 15v0',
        'badge'   => 'M12 2 4 6v6c0 5 3.4 8.7 8 10 4.6-1.3 8-5 8-10V6l-8-4Zm0 8v6',
        'chart'   => 'M4 20V10m6 10V4m6 16v-7',
        'cog'     => 'M10.3 2h3.4l.6 2.6a8 8 0 0 1 2.1 1.2l2.5-1 1.7 3-2 1.7c.1.4.1.9.1 1.3s0 .9-.1 1.3l2 1.7-1.7 3-2.5-1a8 8 0 0 1-2.1 1.2L13.7 20h-3.4l-.6-2.6a8 8 0 0 1-2.1-1.2l-2.5 1-1.7-3 2-1.7A6 6 0 0 1 5.3 12c0-.4 0-.9.1-1.3l-2-1.7 1.7-3 2.5 1a8 8 0 0 1 2.1-1.2L10.3 2Zm1.7 7a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z',
    ];
@endphp

<aside class="flex w-56 shrink-0 flex-col border-r border-border bg-surface-2 px-3 py-4">

    <div class="mb-6 flex items-center gap-2 px-2">
        <svg class="h-6 w-6 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.5 12 4l9 5.5M4 10v9a1 1 0 0 0 1 1h4v-6h6v6h4a1 1 0 0 0 1-1v-9" />
        </svg>
        <span class="text-sm font-semibold">{{ config('app.name', 'Retailo POS') }}</span>
    </div>

    <nav class="flex flex-1 flex-col gap-0.5">
        @foreach ($nav as $item)
            <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
               class="sidebar-link {{ request()->routeIs($item['route'].'*') ? 'active' : '' }}">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$item['icon']] }}" />
                </svg>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="mt-2 flex items-center gap-2 border-t border-border px-2 pt-3">
        <div class="flex h-7 w-7 items-center justify-center rounded-full bg-surface-3 text-xs font-medium">
            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
        </div>
        <div class="min-w-0">
            <div class="truncate text-xs font-medium">{{ auth()->user()->name ?? 'Guest' }}</div>
            <div class="truncate text-xs text-text-muted">{{ auth()->user()->role_name ?? 'Staff' }}</div>
        </div>
    </div>

</aside>

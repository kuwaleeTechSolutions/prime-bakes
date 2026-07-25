<header class="flex items-center justify-between border-b border-border bg-surface-1 px-5 py-3">

    <div class="flex items-center gap-3">
        <h1 class="text-base font-semibold">{{ $header ?? 'Dashboard' }}</h1>

        {{-- Warehouse selector — bind to a Livewire component if you want it to
             actually switch context app-wide (e.g. wire:model on a session-backed property) --}}
        <button class="flex items-center gap-1.5 rounded-md border border-border px-2.5 py-1 text-xs text-text-secondary hover:bg-surface-3">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6" />
            </svg>
            {{ session('active_warehouse', 'Main warehouse') }}
        </button>
    </div>

    <div class="flex items-center gap-4">
        <div class="relative hidden sm:block">
            <svg class="pointer-events-none absolute left-2.5 top-1/2 h-4 w-4 -translate-y-1/2 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <circle cx="11" cy="11" r="7" /><path stroke-linecap="round" d="m20 20-3.5-3.5" />
            </svg>
            <input type="text" placeholder="Search..." class="field-input w-48 pl-8 text-xs">
        </div>

        <button class="relative text-text-secondary hover:text-text-primary" title="Notifications">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0a3 3 0 1 1-6 0m6 0H9" />
            </svg>
            <span class="absolute -right-0.5 -top-0.5 h-2 w-2 rounded-full bg-status-unpaid"></span>
        </button>

        {{-- Cash register status — reflects cash_registers.status for the logged-in user --}}
        <span class="flex items-center gap-1.5 text-xs text-text-secondary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <rect x="2" y="7" width="20" height="10" rx="2" /><circle cx="12" cy="12" r="2" />
            </svg>
            Register open
        </span>

        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex h-7 w-7 items-center justify-center rounded-full bg-surface-3 text-xs font-medium">
                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
            </button>
            <div x-show="open" @click.outside="open = false" x-cloak
                 class="absolute right-0 mt-2 w-40 rounded-lg border border-border bg-surface-1 py-1 shadow-lg">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full px-3 py-1.5 text-left text-xs hover:bg-surface-3">Log out</button>
                </form>
            </div>
        </div>
    </div>

</header>

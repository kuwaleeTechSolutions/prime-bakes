<x-layouts.app :header="'Settings'">
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
        <a href="{{ route('settings.general') }}" class="card hover:bg-surface-3">
            <div class="font-medium">General settings</div>
            <div class="mt-1 text-xs text-text-secondary">Site title, currency, date/invoice format</div>
        </a>
        <a href="{{ route('settings.pos') }}" class="card hover:bg-surface-3">
            <div class="font-medium">POS settings</div>
            <div class="mt-1 text-xs text-text-secondary">Default customer, warehouse, biller</div>
        </a>
        <a href="{{ route('warehouses.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Warehouses</div>
            <div class="mt-1 text-xs text-text-secondary">Branches &amp; stock locations</div>
        </a>
        <a href="{{ route('taxes.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Taxes</div>
            <div class="mt-1 text-xs text-text-secondary">Tax rates used across products</div>
        </a>
        <a href="{{ route('currencies.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Currencies</div>
            <div class="mt-1 text-xs text-text-secondary">Supported currencies &amp; rates</div>
        </a>
        <a href="{{ route('roles.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Roles &amp; permissions</div>
            <div class="mt-1 text-xs text-text-secondary">Define what each staff role can access</div>
        </a>
        <a href="{{ route('users.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Users</div>
            <div class="mt-1 text-xs text-text-secondary">Create and manage staff accounts</div>
        </a>
    </div>
</x-layouts.app>

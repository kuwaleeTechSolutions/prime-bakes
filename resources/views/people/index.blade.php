<x-layouts.app :header="'People'">
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <a href="{{ route('customers.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Customers</div>
            <div class="mt-1 text-xs text-text-secondary">Manage customer records, groups &amp; loyalty</div>
        </a>
        <a href="{{ route('customer-groups.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Customer groups</div>
            <div class="mt-1 text-xs text-text-secondary">Pricing tiers &amp; group discounts</div>
        </a>
        <a href="{{ route('suppliers.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Suppliers</div>
            <div class="mt-1 text-xs text-text-secondary">Vendors you purchase stock from</div>
        </a>
        <a href="{{ route('billers.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Billers</div>
            <div class="mt-1 text-xs text-text-secondary">Branches/staff identities used on invoices</div>
        </a>
    </div>
</x-layouts.app>

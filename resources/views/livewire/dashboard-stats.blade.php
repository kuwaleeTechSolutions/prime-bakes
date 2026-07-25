<div wire:poll.30s="refreshStats" class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
    <div class="stat-card bg-primary-50">
        <div class="text-xs text-text-secondary">Today's sales</div>
        <div class="mt-1 text-xl font-semibold">₹{{ number_format($todaySales, 2) }}</div>
    </div>
    <div class="stat-card bg-secondary-50">
        <div class="text-xs text-text-secondary">Today's purchases</div>
        <div class="mt-1 text-xl font-semibold">₹{{ number_format($todayPurchases, 2) }}</div>
    </div>
    <div class="stat-card bg-red-50">
        <div class="text-xs text-text-secondary">Low stock items</div>
        <div class="mt-1 text-xl font-semibold">{{ $lowStockCount }}</div>
    </div>
    <div class="stat-card border border-border bg-surface-1">
        <div class="text-xs text-text-secondary">Cash in register</div>
        <div class="mt-1 text-xl font-semibold">₹{{ number_format($cashInRegister, 2) }}</div>
    </div>
</div>

<div>
    <div class="mb-4 flex flex-wrap items-end gap-2">
        <div>
            <label class="field-label">From</label>
            <input type="date" wire:model.live="from_date" class="field-input">
        </div>
        <div>
            <label class="field-label">To</label>
            <input type="date" wire:model.live="to_date" class="field-input">
        </div>
        <div>
            <label class="field-label">Warehouse</label>
            <select wire:model.live="warehouseFilter" class="field-input">
                <option value="">All warehouses</option>
                @foreach ($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
        <div class="stat-card bg-primary-50">
            <div class="text-xs text-text-secondary">Sales</div>
            <div class="mt-1 text-lg font-semibold">{{ $totals->sale_count ?? 0 }}</div>
        </div>
        <div class="stat-card bg-secondary-50">
            <div class="text-xs text-text-secondary">Revenue</div>
            <div class="mt-1 text-lg font-semibold">₹{{ number_format($totals->grand_total ?? 0, 2) }}</div>
        </div>
        <div class="stat-card border border-border bg-surface-1">
            <div class="text-xs text-text-secondary">Collected</div>
            <div class="mt-1 text-lg font-semibold">₹{{ number_format($totals->paid_amount ?? 0, 2) }}</div>
        </div>
        <div class="stat-card bg-red-50">
            <div class="text-xs text-text-secondary">Outstanding</div>
            <div class="mt-1 text-lg font-semibold">₹{{ number_format(($totals->grand_total ?? 0) - ($totals->paid_amount ?? 0), 2) }}</div>
        </div>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Reference</th><th>Date</th><th>Customer</th><th>Warehouse</th><th class="text-right">Total</th><th>Status</th></tr></thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr wire:key="sr-{{ $sale->id }}">
                        <td class="font-medium">#{{ $sale->reference_no }}</td>
                        <td>{{ $sale->sale_date?->format('d M Y') ?? $sale->created_at->format('d M Y') }}</td>
                        <td>{{ $sale->customer?->name }}</td>
                        <td>{{ $sale->warehouse?->name }}</td>
                        <td class="text-right">₹{{ number_format($sale->grand_total, 2) }}</td>
                        <td><span class="pill-{{ $sale->payment_status }}">{{ ucfirst($sale->payment_status) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-8 text-center text-text-muted">No sales in this range.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $sales->links() }}</div>
</div>

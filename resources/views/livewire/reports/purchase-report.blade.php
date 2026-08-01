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

    <div class="mb-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
        <div class="stat-card bg-primary-50">
            <div class="text-xs text-text-secondary">Purchases</div>
            <div class="mt-1 text-lg font-semibold">{{ $totals->purchase_count ?? 0 }}</div>
        </div>
        <div class="stat-card bg-secondary-50">
            <div class="text-xs text-text-secondary">Total spend</div>
            <div class="mt-1 text-lg font-semibold">₹{{ number_format($totals->grand_total ?? 0, 2) }}</div>
        </div>
        <div class="stat-card bg-red-50">
            <div class="text-xs text-text-secondary">Owed to suppliers</div>
            <div class="mt-1 text-lg font-semibold">₹{{ number_format(($totals->grand_total ?? 0) - ($totals->paid_amount ?? 0), 2) }}</div>
        </div>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Reference</th><th>Date</th><th>Supplier</th><th>Warehouse</th><th class="text-right">Total</th><th>Status</th></tr></thead>
            <tbody>
                @forelse ($purchases as $purchase)
                    <tr wire:key="pr-{{ $purchase->id }}">
                        <td class="font-medium">{{ $purchase->reference_no }}</td>
                        <td>{{ $purchase->purchase_date?->format('d M Y') ?? $purchase->created_at->format('d M Y') }}</td>
                        <td>{{ $purchase->supplier?->name ?? '—' }}</td>
                        <td>{{ $purchase->warehouse?->name }}</td>
                        <td class="text-right">₹{{ number_format($purchase->grand_total, 2) }}</td>
                        <td><span class="pill-{{ $purchase->payment_status }}">{{ ucfirst($purchase->payment_status) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-8 text-center text-text-muted">No purchases in this range.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $purchases->links() }}</div>
</div>

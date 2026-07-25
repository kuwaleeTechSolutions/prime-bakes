<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex flex-wrap items-center gap-2">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search reference/invoice no..." class="field-input w-64">

        <select wire:model.live="supplierFilter" class="field-input w-44">
            <option value="">All suppliers</option>
            @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="statusFilter" class="field-input w-36">
            <option value="">Any status</option>
            <option value="pending">Pending</option>
            <option value="ordered">Ordered</option>
            <option value="received">Received</option>
        </select>

        <select wire:model.live="paymentStatusFilter" class="field-input w-36">
            <option value="">Any payment</option>
            <option value="paid">Paid</option>
            <option value="partial">Partial</option>
            <option value="unpaid">Unpaid</option>
        </select>

        <a href="{{ route('purchases.create') }}" class="btn-primary ml-auto">+ Add purchase</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead>
                <tr>
                    <th>Reference</th><th>Supplier</th><th>Warehouse</th>
                    <th class="text-right">Grand total</th><th class="text-right">Due</th>
                    <th>Status</th><th>Payment</th><th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($purchases as $purchase)
                    <tr wire:key="purchase-{{ $purchase->id }}">
                        <td class="font-medium">{{ $purchase->reference_no }}</td>
                        <td>{{ $purchase->supplier?->name ?? '—' }}</td>
                        <td>{{ $purchase->warehouse?->name }}</td>
                        <td class="text-right">₹{{ number_format($purchase->grand_total, 2) }}</td>
                        <td class="text-right {{ $purchase->due > 0 ? 'text-status-unpaid' : '' }}">₹{{ number_format($purchase->due, 2) }}</td>
                        <td><span class="pill-{{ $purchase->status === 'received' ? 'paid' : 'pending' }}">{{ ucfirst($purchase->status) }}</span></td>
                        <td><span class="pill-{{ $purchase->payment_status }}">{{ ucfirst($purchase->payment_status) }}</span></td>
                        <td class="text-right"><a href="{{ route('purchases.edit', $purchase) }}" class="text-text-accent text-xs hover:underline">View / Edit</a></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="py-8 text-center text-text-muted">No purchases yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $purchases->links() }}</div>
</div>

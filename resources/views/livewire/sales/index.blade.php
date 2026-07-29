<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex flex-wrap items-center gap-2">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search reference no..." class="field-input w-64">

        <select wire:model.live="statusFilter" class="field-input w-36">
            <option value="">Any status</option>
            <option value="pending">Pending</option>
            <option value="hold">Hold</option>
            <option value="completed">Completed</option>
        </select>

        <select wire:model.live="paymentStatusFilter" class="field-input w-36">
            <option value="">Any payment</option>
            <option value="paid">Paid</option>
            <option value="partial">Partial</option>
            <option value="unpaid">Unpaid</option>
        </select>

        <a href="{{ route('pos.index') }}" class="btn-primary ml-auto">Open POS</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead>
                <tr>
                    <th>Date</th><th>Customer</th><th>Warehouse</th><th>Cashier</th>
                    <th class="text-right">Grand total</th><th class="text-right">Due</th>
                    <th>Status</th><th>Payment</th><th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr wire:key="sale-{{ $sale->id }}">
                        <td class="font-medium">{{ $sale->created_at }}</td>
                        <td>{{ $sale->customer?->name }}</td>
                        <td>{{ $sale->warehouse?->name }}</td>
                        <td>{{ $sale->user?->name }}</td>
                        <td class="text-right">₹{{ number_format($sale->grand_total, 2) }}</td>
                        <td class="text-right {{ $sale->due > 0 ? 'text-status-unpaid' : '' }}">₹{{ number_format($sale->due, 2) }}</td>
                        <td><span class="pill-{{ $sale->sale_status === 'completed' ? 'paid' : 'pending' }}">{{ ucfirst($sale->sale_status) }}</span></td>
                        <td><span class="pill-{{ $sale->payment_status }}">{{ ucfirst($sale->payment_status) }}</span></td>
                        <td class="text-right"><a href="{{ route('sales.show', $sale) }}" class="text-text-accent text-xs hover:underline">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="py-8 text-center text-text-muted">No sales yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $sales->links() }}</div>
</div>

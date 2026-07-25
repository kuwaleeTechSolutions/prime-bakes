<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex flex-wrap items-center gap-2">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search reference..." class="field-input w-64">
        <select wire:model.live="statusFilter" class="field-input w-36">
            <option value="">Any status</option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
        </select>
        <a href="{{ route('transfers.create') }}" class="btn-primary ml-auto">+ New transfer</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Reference</th><th>From</th><th>To</th><th class="text-right">Qty</th><th class="text-right">Grand total</th><th>Status</th></tr></thead>
            <tbody>
                @forelse ($transfers as $transfer)
                    <tr wire:key="transfer-{{ $transfer->id }}">
                        <td class="font-medium">{{ $transfer->reference_no }}</td>
                        <td>{{ $transfer->fromWarehouse?->name }}</td>
                        <td>{{ $transfer->toWarehouse?->name }}</td>
                        <td class="text-right">{{ $transfer->total_qty }}</td>
                        <td class="text-right">₹{{ number_format($transfer->grand_total, 2) }}</td>
                        <td><span class="pill-{{ $transfer->status === 'completed' ? 'paid' : 'pending' }}">{{ ucfirst($transfer->status) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-8 text-center text-text-muted">No transfers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $transfers->links() }}</div>
</div>

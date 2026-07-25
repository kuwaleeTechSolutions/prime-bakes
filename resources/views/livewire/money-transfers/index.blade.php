<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex justify-end">
        <a href="{{ route('money-transfers.create') }}" class="btn-primary">+ New transfer</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Reference</th><th>From</th><th>To</th><th class="text-right">Amount</th><th>Note</th></tr></thead>
            <tbody>
                @forelse ($transfers as $transfer)
                    <tr wire:key="mt-{{ $transfer->id }}">
                        <td class="font-medium">{{ $transfer->reference_no }}</td>
                        <td>{{ $transfer->fromAccount?->name }}</td>
                        <td>{{ $transfer->toAccount?->name }}</td>
                        <td class="text-right">₹{{ number_format($transfer->amount, 2) }}</td>
                        <td class="text-text-secondary">{{ $transfer->note ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-text-muted">No transfers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $transfers->links() }}</div>
</div>

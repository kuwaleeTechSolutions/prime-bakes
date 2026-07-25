<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex justify-end">
        <a href="{{ route('stock-counts.create') }}" class="btn-primary">+ Start stock count</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Reference</th><th>Warehouse</th><th>Type</th><th>Reconciled</th><th>Note</th></tr></thead>
            <tbody>
                @forelse ($counts as $count)
                    <tr wire:key="sc-{{ $count->id }}">
                        <td class="font-medium">{{ $count->reference_no }}</td>
                        <td>{{ $count->warehouse?->name }}</td>
                        <td class="capitalize">{{ $count->type }}</td>
                        <td><span class="{{ $count->is_adjusted ? 'pill-paid' : 'pill-pending' }}">{{ $count->is_adjusted ? 'Adjusted' : 'No changes' }}</span></td>
                        <td class="text-text-secondary">{{ $count->note ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-text-muted">No stock counts yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $counts->links() }}</div>
</div>

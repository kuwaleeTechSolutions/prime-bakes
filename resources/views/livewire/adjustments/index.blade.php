<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex items-center gap-2">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search reference..." class="field-input w-64">
        <a href="{{ route('adjustments.create') }}" class="btn-primary ml-auto">+ New adjustment</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Reference</th><th>Warehouse</th><th class="text-right">Items</th><th class="text-right">Total qty</th><th>Note</th></tr></thead>
            <tbody>
                @forelse ($adjustments as $adjustment)
                    <tr wire:key="adj-{{ $adjustment->id }}">
                        <td class="font-medium">{{ $adjustment->reference_no }}</td>
                        <td>{{ $adjustment->warehouse?->name }}</td>
                        <td class="text-right">{{ $adjustment->item }}</td>
                        <td class="text-right">{{ $adjustment->total_qty }}</td>
                        <td class="text-text-secondary">{{ $adjustment->note ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-text-muted">No adjustments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $adjustments->links() }}</div>
</div>

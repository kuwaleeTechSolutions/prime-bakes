<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex items-center gap-2">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search reference..." class="field-input w-64">
        <a href="{{ route('damages.create') }}" class="btn-primary ml-auto">+ Log damage</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Reference</th><th>Warehouse</th><th class="text-right">Items</th><th class="text-right">Cost</th><th class="text-right">Grand total</th></tr></thead>
            <tbody>
                @forelse ($damages as $damage)
                    <tr wire:key="dmg-{{ $damage->id }}">
                        <td class="font-medium">{{ $damage->reference_no }}</td>
                        <td>{{ $damage->fromWarehouse?->name }}</td>
                        <td class="text-right">{{ $damage->item }}</td>
                        <td class="text-right">₹{{ number_format($damage->total_cost, 2) }}</td>
                        <td class="text-right">₹{{ number_format($damage->grand_total, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-text-muted">No damages logged yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $damages->links() }}</div>
</div>

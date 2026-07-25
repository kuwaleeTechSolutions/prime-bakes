<div>
    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Cashier</th><th>Warehouse</th><th class="text-right">Opening cash</th><th class="text-right">Cash sales</th><th>Status</th><th>Opened</th></tr></thead>
            <tbody>
                @forelse ($registers as $register)
                    <tr wire:key="reg-{{ $register->id }}">
                        <td class="font-medium">{{ $register->user?->name }}</td>
                        <td>{{ $register->warehouse?->name }}</td>
                        <td class="text-right">₹{{ number_format($register->cash_in_hand, 2) }}</td>
                        <td class="text-right">₹{{ number_format($register->cash_sales_total, 2) }}</td>
                        <td><span class="{{ $register->status ? 'pill-paid' : 'pill-pending' }}">{{ $register->status ? 'Open' : 'Closed' }}</span></td>
                        <td class="text-text-secondary">{{ $register->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-8 text-center text-text-muted">No register sessions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $registers->links() }}</div>
</div>

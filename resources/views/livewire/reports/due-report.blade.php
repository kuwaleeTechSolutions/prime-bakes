<div>
    <div class="mb-4 flex gap-2">
        <button wire:click="$set('tab', 'customers')" class="{{ $tab === 'customers' ? 'btn-primary' : 'btn-outline' }}">Customers</button>
        <button wire:click="$set('tab', 'suppliers')" class="{{ $tab === 'suppliers' ? 'btn-primary' : 'btn-outline' }}">Suppliers</button>
    </div>

    @if ($tab === 'customers')
        <div class="card overflow-x-auto">
            <table class="table-base">
                <thead><tr><th>Customer</th><th>Phone</th><th class="text-right">Amount due</th></tr></thead>
                <tbody>
                    @forelse ($customerDues as $row)
                        <tr>
                            <td class="font-medium">{{ $row->name }}</td>
                            <td>{{ $row->phone }}</td>
                            <td class="text-right text-status-unpaid">₹{{ number_format($row->due, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-8 text-center text-text-muted">No outstanding customer dues.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="card overflow-x-auto">
            <table class="table-base">
                <thead><tr><th>Supplier</th><th>Phone</th><th class="text-right">Amount owed</th></tr></thead>
                <tbody>
                    @forelse ($supplierDues as $row)
                        <tr>
                            <td class="font-medium">{{ $row->name }}</td>
                            <td>{{ $row->phone }}</td>
                            <td class="text-right text-status-unpaid">₹{{ number_format($row->due, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-8 text-center text-text-muted">No outstanding supplier dues.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>

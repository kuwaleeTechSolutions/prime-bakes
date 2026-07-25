<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex justify-end">
        <button wire:click="create" class="btn-primary">+ Add deposit</button>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Customer</th><th class="text-right">Amount</th><th>Note</th><th>Date</th></tr></thead>
            <tbody>
                @forelse ($deposits as $deposit)
                    <tr wire:key="dep-{{ $deposit->id }}">
                        <td class="font-medium">{{ $deposit->customer?->name }}</td>
                        <td class="text-right">₹{{ number_format($deposit->amount, 2) }}</td>
                        <td class="text-text-secondary">{{ $deposit->note ?? '—' }}</td>
                        <td class="text-text-secondary">{{ $deposit->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-8 text-center text-text-muted">No deposits yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $deposits->links() }}</div>

    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30" wire:click.self="$set('showForm', false)">
            <form wire:submit="save" class="card w-full max-w-sm bg-surface-1 space-y-4">
                <div class="font-medium">Add deposit</div>
                <div>
                    <label class="field-label">Customer</label>
                    <select wire:model="customer_id" class="field-input">
                        <option value="">Select customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }} (current: ₹{{ number_format($customer->deposit, 2) }})</option>
                        @endforeach
                    </select>
                    @error('customer_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Amount</label>
                    <input type="number" step="0.01" wire:model="amount" class="field-input">
                    @error('amount') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label">Note</label>
                    <input type="text" wire:model="note" class="field-input">
                </div>
                <div class="flex justify-end gap-2 border-t border-border pt-3">
                    <button type="button" class="btn-outline" wire:click="$set('showForm', false)">Cancel</button>
                    <button type="submit" class="btn-primary">Save</button>
                </div>
            </form>
        </div>
    @endif
</div>

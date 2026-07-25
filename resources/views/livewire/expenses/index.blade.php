<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex flex-wrap items-center gap-2">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search reference/note..." class="field-input w-64">
        <select wire:model.live="categoryFilter" class="field-input w-44">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <a href="{{ route('expenses.create') }}" class="btn-primary ml-auto">+ Add expense</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Reference</th><th>Category</th><th>Warehouse</th><th>Account</th><th class="text-right">Amount</th><th>Note</th></tr></thead>
            <tbody>
                @forelse ($expenses as $expense)
                    <tr wire:key="exp-{{ $expense->id }}">
                        <td class="font-medium">{{ $expense->reference_no }}</td>
                        <td>{{ $expense->category?->name }}</td>
                        <td>{{ $expense->warehouse?->name }}</td>
                        <td>{{ $expense->account?->name }}</td>
                        <td class="text-right">₹{{ number_format($expense->amount, 2) }}</td>
                        <td class="text-text-secondary">{{ $expense->note ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-8 text-center text-text-muted">No expenses yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $expenses->links() }}</div>
</div>

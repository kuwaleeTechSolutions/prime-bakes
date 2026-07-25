<div class="max-w-lg">
    <div class="mb-4 flex flex-wrap items-end gap-2">
        <div>
            <label class="field-label">From</label>
            <input type="date" wire:model.live="from_date" class="field-input">
        </div>
        <div>
            <label class="field-label">To</label>
            <input type="date" wire:model.live="to_date" class="field-input">
        </div>
    </div>

    <div class="card">
        <div class="mb-3 flex justify-between text-sm font-medium">
            <span>Total expenses</span><span>₹{{ number_format($total, 2) }}</span>
        </div>
        <div class="space-y-2">
            @forelse ($byCategory as $category => $amount)
                <div>
                    <div class="mb-1 flex justify-between text-sm">
                        <span>{{ $category }}</span><span>₹{{ number_format($amount, 2) }}</span>
                    </div>
                    <div class="h-1.5 w-full rounded-full bg-surface-3">
                        <div class="h-1.5 rounded-full bg-primary-500" style="width: {{ $total > 0 ? min(100, $amount / $total * 100) : 0 }}%"></div>
                    </div>
                </div>
            @empty
                <p class="py-6 text-center text-sm text-text-muted">No expenses in this range.</p>
            @endforelse
        </div>
    </div>
</div>

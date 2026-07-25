<div class="flex min-h-[70vh] items-center justify-center">
    <form wire:submit="close" class="card w-full max-w-sm space-y-4">
        <div>
            <div class="text-lg font-semibold">Close register</div>
            <p class="text-sm text-text-secondary">Count the drawer and confirm before ending your shift.</p>
        </div>

        <div class="rounded-lg border border-border bg-surface-2 p-3 text-sm">
            <div class="flex justify-between"><span class="text-text-secondary">Opening cash</span><span>₹{{ number_format($cashRegister->cash_in_hand, 2) }}</span></div>
            <div class="flex justify-between"><span class="text-text-secondary">Cash sales this shift</span><span>₹{{ number_format($cashRegister->cash_sales_total, 2) }}</span></div>
            <div class="mt-2 flex justify-between border-t border-border pt-2 font-medium"><span>Expected in drawer</span><span>₹{{ number_format($cashRegister->cash_in_hand + $cashRegister->cash_sales_total, 2) }}</span></div>
        </div>

        <div>
            <label class="field-label">Counted cash</label>
            <input type="number" step="0.01" wire:model="counted_cash" class="field-input">
        </div>

        @php $diff = $counted_cash - ($cashRegister->cash_in_hand + $cashRegister->cash_sales_total); @endphp
        @if ($diff != 0)
            <p class="text-xs {{ $diff > 0 ? 'text-primary-600' : 'text-status-unpaid' }}">
                {{ $diff > 0 ? 'Over' : 'Short' }} by ₹{{ number_format(abs($diff), 2) }}
            </p>
        @endif

        <button type="submit" class="btn-primary w-full">Close register</button>
    </form>
</div>

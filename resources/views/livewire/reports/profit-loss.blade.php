<div class="max-w-xl">
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

    <div class="card space-y-2 text-sm">
        <div class="flex justify-between"><span class="text-text-secondary">Revenue (sales)</span><span>₹{{ number_format($revenue, 2) }}</span></div>
        <div class="flex justify-between"><span class="text-text-secondary">Cost of goods sold</span><span>−₹{{ number_format($cogs, 2) }}</span></div>
        <div class="flex justify-between border-t border-border pt-2 font-medium"><span>Gross profit</span><span>₹{{ number_format($grossProfit, 2) }}</span></div>

        <div class="flex justify-between pt-3"><span class="text-text-secondary">Operating expenses</span><span>−₹{{ number_format($expenses, 2) }}</span></div>
        <div class="flex justify-between"><span class="text-text-secondary">Payroll</span><span>−₹{{ number_format($payroll, 2) }}</span></div>
        <div class="flex justify-between border-t border-border pt-2 text-base font-semibold {{ $netProfit < 0 ? 'text-status-unpaid' : '' }}">
            <span>Net profit</span><span>₹{{ number_format($netProfit, 2) }}</span>
        </div>

        <div class="mt-3 border-t border-border pt-3 text-xs text-text-muted">
            Stock purchased this period (not a P&amp;L line — shown for reference): ₹{{ number_format($purchases, 2) }}
        </div>
    </div>

    <p class="mt-3 text-xs text-text-muted">
        Cost of goods sold is estimated as (quantity sold × the product's current cost price),
        not true FIFO/weighted-average costing — precise costing needs purchase-batch cost
        tracking through the sale, which isn't wired up yet.
    </p>
</div>

<div class="max-w-md">
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
        <div class="flex justify-between"><span class="text-text-secondary">Tax collected (sales)</span><span>₹{{ number_format($taxCollected, 2) }}</span></div>
        <div class="flex justify-between"><span class="text-text-secondary">Tax paid (purchases)</span><span>−₹{{ number_format($taxPaid, 2) }}</span></div>
        <div class="flex justify-between border-t border-border pt-2 text-base font-semibold"><span>Net tax liability</span><span>₹{{ number_format($netTax, 2) }}</span></div>
    </div>
    <p class="mt-3 text-xs text-text-muted">Figures reflect tax amounts already computed and stored on each sale/purchase at the time of entry — this report totals them, it doesn't recompute from line items.</p>
</div>

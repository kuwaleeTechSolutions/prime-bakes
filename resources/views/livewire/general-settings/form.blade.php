<div class="max-w-2xl">
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <form wire:submit="save" class="card space-y-4">
        <div>
            <label class="field-label">Site title</label>
            <input type="text" wire:model="site_title" class="field-input">
            @error('site_title') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="field-label">Currency code</label>
                <input type="text" wire:model="currency" class="field-input" placeholder="INR">
                @error('currency') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="field-label">Currency symbol position</label>
                <select wire:model="currency_position" class="field-input">
                    <option value="prefix">Prefix (₹100)</option>
                    <option value="suffix">Suffix (100₹)</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="field-label">Date format</label>
                <select wire:model="date_format" class="field-input">
                    <option value="d-m-Y">DD-MM-YYYY</option>
                    <option value="m-d-Y">MM-DD-YYYY</option>
                    <option value="Y-m-d">YYYY-MM-DD</option>
                </select>
            </div>
            <div>
                <label class="field-label">Invoice format</label>
                <select wire:model="invoice_format" class="field-input">
                    <option value="standard">Standard</option>
                    <option value="simple">Simple</option>
                </select>
            </div>
        </div>

        <div>
            <label class="field-label">Staff access</label>
            <select wire:model="staff_access" class="field-input">
                <option value="own_warehouse">Own warehouse only</option>
                <option value="all">All warehouses</option>
            </select>
            <p class="mt-1 text-xs text-text-muted">Controls whether staff-level users (see the People module's <code>isOwnerLevel()</code>) can view data outside their assigned warehouse. Enforce this in your query scopes/policies — this setting is the switch, not the enforcement.</p>
        </div>

        <div class="flex items-center gap-6 text-sm">
            <label class="flex items-center gap-2"><input type="checkbox" wire:model="is_rtl"> Right-to-left layout</label>
            <label class="flex items-center gap-2"><input type="checkbox" wire:model="cash_register"> Require open cash register for POS</label>
        </div>

        <div class="flex justify-end border-t border-border pt-3">
            <button type="submit" class="btn-primary">Save settings</button>
        </div>
    </form>
</div>

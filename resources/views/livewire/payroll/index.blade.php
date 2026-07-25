<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex justify-end">
        <button wire:click="create" class="btn-primary">+ Pay salary</button>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Reference</th><th>Employee</th><th>Month</th><th>Account</th><th class="text-right">Amount</th><th>Note</th></tr></thead>
            <tbody>
                @forelse ($payrolls as $payroll)
                    <tr wire:key="pr-{{ $payroll->id }}">
                        <td class="font-medium">{{ $payroll->reference_no }}</td>
                        <td>{{ $payroll->employee?->name }}</td>
                        <td>{{ $payroll->month }}</td>
                        <td>{{ $payroll->account?->name }}</td>
                        <td class="text-right">₹{{ number_format($payroll->amount, 2) }}</td>
                        <td class="text-text-secondary">{{ $payroll->note ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-8 text-center text-text-muted">No salaries paid yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $payrolls->links() }}</div>

    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30" wire:click.self="$set('showForm', false)">
            <form wire:submit="save" class="card w-full max-w-sm bg-surface-1 space-y-4">
                <div class="font-medium">Pay salary</div>

                <div>
                    <label class="field-label">Employee</label>
                    <select wire:model.live="employee_id" class="field-input">
                        <option value="">Select employee</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                    @error('employee_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="field-label">Month</label>
                        <input type="text" wire:model="month" placeholder="e.g. July" class="field-input">
                        @error('month') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="field-label">Amount</label>
                        <input type="number" step="0.01" wire:model="amount" class="field-input">
                        @error('amount') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="field-label">Pay from account</label>
                    <select wire:model="account_id" class="field-input">
                        <option value="">Select account</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }} (₹{{ number_format($account->total_balance, 2) }})</option>
                        @endforeach
                    </select>
                    @error('account_id') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="field-label">Paying method</label>
                    <input type="text" wire:model="paying_method" class="field-input">
                </div>

                <div>
                    <label class="field-label">Note</label>
                    <input type="text" wire:model="note" class="field-input">
                </div>

                <div class="flex justify-end gap-2 border-t border-border pt-3">
                    <button type="button" class="btn-outline" wire:click="$set('showForm', false)">Cancel</button>
                    <button type="submit" class="btn-primary">Pay</button>
                </div>
            </form>
        </div>
    @endif
</div>

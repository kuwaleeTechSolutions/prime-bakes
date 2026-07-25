<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex justify-end">
        <button wire:click="create" class="btn-primary">+ Request leave</button>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Requested by</th><th>From</th><th>To</th><th>Days</th><th>Note</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($holidays as $holiday)
                    <tr wire:key="hol-{{ $holiday->id }}">
                        <td class="font-medium">{{ $holiday->user?->name }}</td>
                        <td>{{ $holiday->from_date->format('d M Y') }}</td>
                        <td>{{ $holiday->to_date->format('d M Y') }}</td>
                        <td>{{ $holiday->days }}</td>
                        <td class="text-text-secondary">{{ $holiday->note ?? '—' }}</td>
                        <td><span class="{{ $holiday->is_approved ? 'pill-paid' : 'pill-pending' }}">{{ $holiday->is_approved ? 'Approved' : 'Pending' }}</span></td>
                        <td class="text-right">
                            @unless ($holiday->is_approved)
                                <button wire:click="approve({{ $holiday->id }})" class="text-text-accent text-xs hover:underline">Approve</button>
                                <button wire:click="reject({{ $holiday->id }})" class="ml-3 text-xs text-status-unpaid hover:underline">Reject</button>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-8 text-center text-text-muted">No leave requests yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $holidays->links() }}</div>

    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30" wire:click.self="$set('showForm', false)">
            <form wire:submit="save" class="card w-full max-w-sm bg-surface-1 space-y-4">
                <div class="font-medium">Request leave</div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="field-label">From</label>
                        <input type="date" wire:model="from_date" class="field-input">
                        @error('from_date') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="field-label">To</label>
                        <input type="date" wire:model="to_date" class="field-input">
                        @error('to_date') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="field-label">Reason</label>
                    <textarea wire:model="note" rows="2" class="field-input"></textarea>
                </div>
                <div class="flex justify-end gap-2 border-t border-border pt-3">
                    <button type="button" class="btn-outline" wire:click="$set('showForm', false)">Cancel</button>
                    <button type="submit" class="btn-primary">Submit</button>
                </div>
            </form>
        </div>
    @endif
</div>

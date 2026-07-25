<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex items-center gap-2">
        <label class="field-label mb-0">Date</label>
        <input type="date" wire:model.live="date" class="field-input w-44">
    </div>

    <form wire:submit="save" class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Employee</th><th class="w-32">Check in</th><th class="w-32">Check out</th><th class="w-36">Status</th><th>Note</th></tr></thead>
            <tbody>
                @forelse ($rows as $index => $row)
                    <tr wire:key="att-{{ $row['employee_id'] }}">
                        <td class="font-medium">{{ $row['name'] }}</td>
                        <td><input type="text" wire:model="rows.{{ $index }}.checkin" class="field-input"></td>
                        <td><input type="text" wire:model="rows.{{ $index }}.checkout" class="field-input"></td>
                        <td>
                            <select wire:model="rows.{{ $index }}.status" class="field-input">
                                <option value="present">Present</option>
                                <option value="late">Late</option>
                                <option value="half_day">Half day</option>
                                <option value="absent">Absent</option>
                            </select>
                        </td>
                        <td><input type="text" wire:model="rows.{{ $index }}.note" class="field-input"></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-text-muted">No active employees to mark.</td></tr>
                @endforelse
            </tbody>
        </table>

        @if (count($rows))
            <div class="mt-4 flex justify-end border-t border-border pt-4">
                <button type="submit" class="btn-primary">Save attendance</button>
            </div>
        @endif
    </form>
</div>

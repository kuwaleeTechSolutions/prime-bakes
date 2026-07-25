<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex flex-wrap items-center gap-2">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search name, phone, company..." class="field-input w-64">

        <select wire:model.live="groupFilter" class="field-input w-44">
            <option value="">All groups</option>
            @foreach ($groups as $group)
                <option value="{{ $group->id }}">{{ $group->name }}</option>
            @endforeach
        </select>

        <a href="{{ route('customers.create') }}" class="btn-primary ml-auto">+ Add customer</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead>
                <tr>
                    <th>Name</th><th>Phone</th><th>Group</th>
                    <th class="text-right">Points</th><th class="text-right">Deposit</th>
                    <th>Status</th><th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr wire:key="cust-{{ $customer->id }}">
                        <td class="font-medium">{{ $customer->name }}</td>
                        <td>{{ $customer->phone_number }}</td>
                        <td>{{ $customer->group?->name ?? '—' }}</td>
                        <td class="text-right">{{ number_format($customer->points, 0) }}</td>
                        <td class="text-right">₹{{ number_format($customer->deposit, 2) }}</td>
                        <td>
                            <button wire:click="toggleActive({{ $customer->id }})"
                                    class="{{ $customer->is_active ? 'pill-paid' : 'pill-pending' }}">
                                {{ $customer->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="text-right"><a href="{{ route('customers.edit', $customer) }}" class="text-text-accent text-xs hover:underline">Edit</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-8 text-center text-text-muted">No customers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $customers->links() }}</div>
</div>

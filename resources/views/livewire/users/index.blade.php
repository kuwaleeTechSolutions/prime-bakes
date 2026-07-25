<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-status-unpaid">{{ session('error') }}</div>
    @endif

    <div class="mb-4 flex flex-wrap items-center gap-2">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search name/email..." class="field-input w-64">
        <select wire:model.live="roleFilter" class="field-input w-44">
            <option value="">All roles</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>
        <a href="{{ route('users.create') }}" class="btn-primary ml-auto">+ Add user</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Warehouse</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($users as $user)
                    <tr wire:key="user-{{ $user->id }}">
                        <td class="font-medium">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role?->name }}</td>
                        <td>{{ $user->warehouse?->name ?? 'All (owner-level)' }}</td>
                        <td>
                            <button wire:click="toggleActive({{ $user->id }})" class="{{ $user->is_active ? 'pill-paid' : 'pill-pending' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="text-right"><a href="{{ route('users.edit', $user) }}" class="text-text-accent text-xs hover:underline">Edit</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-8 text-center text-text-muted">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
</div>

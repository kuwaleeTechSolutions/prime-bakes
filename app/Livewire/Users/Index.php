<?php

namespace App\Livewire\Users;

use App\Models\Role;
use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public string $roleFilter = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingRoleFilter() { $this->resetPage(); }

    public function toggleActive(int $userId): void
    {
        if ($userId === auth()->id()) {
            session()->flash('error', "You can't deactivate your own account.");
            return;
        }

        $user = User::findOrFail($userId);
        $user->update(['is_active' => ! $user->is_active]);
    }

    public function render()
    {
        $users = User::query()
            ->with(['role', 'warehouse', 'biller'])
            ->where('is_deleted', false)
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"))
            ->when($this->roleFilter, fn ($q) => $q->where('role_id', $this->roleFilter))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.users.index', [
            'users' => $users,
            'roles' => Role::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}

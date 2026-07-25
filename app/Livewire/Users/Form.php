<?php

namespace App\Livewire\Users;

use App\Models\Biller;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Form extends Component
{
    #[Locked]
    public ?int $userId = null;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public ?int $role_id = null;
    public ?int $warehouse_id = null;
    public ?int $biller_id = null;
    public string $password = '';
    public string $password_confirmation = '';
    public bool $is_active = true;

    public function mount(?User $user = null): void
    {
        if ($user?->exists) {
            $this->userId = $user->id;
            $this->fill($user->only(['name', 'email', 'phone', 'role_id', 'warehouse_id', 'biller_id', 'is_active']));
        }
    }

    public function save()
    {
        $rules = [
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191', 'unique:users,email,' . $this->userId],
            'phone' => ['required', 'string', 'max:191'],
            'role_id' => ['required', 'exists:roles,id'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'biller_id' => ['nullable', 'exists:billers,id'],
        ];

        // Password is required on create, optional on edit (blank = keep current).
        $rules['password'] = $this->userId
            ? ['nullable', 'string', 'min:8', 'confirmed']
            : ['required', 'string', 'min:8', 'confirmed'];

        $validated = $this->validate($rules);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $validated['is_active'] = $this->is_active;
        $validated['is_deleted'] = false;

        User::updateOrCreate(['id' => $this->userId], $validated);

        session()->flash('success', $this->userId ? 'User updated.' : 'User created.');

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.form', [
            'roles' => Role::where('is_active', true)->orderBy('name')->get(),
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
            'billers' => Biller::active()->orderBy('name')->get(),
        ]);
    }
}

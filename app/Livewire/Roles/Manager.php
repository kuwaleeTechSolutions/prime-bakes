<?php

namespace App\Livewire\Roles;

use App\Models\Permission;
use App\Models\Role;
use Livewire\Component;

class Manager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $description = '';
    public bool $is_active = true;
    public array $selectedPermissions = [];

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'description', 'selectedPermissions']);
        $this->is_active = true;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $role = Role::with('permissions')->findOrFail($id);
        $this->editingId = $role->id;
        $this->name = $role->name;
        $this->description = $role->description ?? '';
        $this->is_active = $role->is_active;
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate(['name' => ['required', 'string', 'max:191']]);

        $role = Role::updateOrCreate(['id' => $this->editingId], [
            'name' => $this->name,
            'description' => $this->description ?: null,
            'guard_name' => 'web',
            'is_active' => $this->is_active,
        ]);

        $role->permissions()->sync($this->selectedPermissions);

        $this->showForm = false;
        session()->flash('success', 'Role saved.');
    }

    public function delete(int $id): void
    {
        Role::whereKey($id)->update(['is_active' => false]);
    }

    public function render()
    {
        return view('livewire.roles.manager', [
            'roles' => Role::withCount('users')->orderBy('name')->get(),
            // Grouped by the text before the first dot (e.g. "products.create" -> "products")
            // so the checkbox list reads as sections instead of one long flat list.
            'permissionGroups' => Permission::orderBy('name')->get()->groupBy(
                fn ($permission) => str_contains($permission->name, '.') ? explode('.', $permission->name)[0] : 'general'
            ),
        ]);
    }
}

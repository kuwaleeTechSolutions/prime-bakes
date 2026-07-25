<?php

namespace App\Livewire\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

/**
 * Self-registration for a business owner/manager account.
 *
 * In the source schema, staff accounts (role "Cashier" etc.) always carry a
 * warehouse_id/biller_id and are created by an admin from the Users screen —
 * they are NOT expected to self-register. Only owner-level accounts
 * (warehouse_id/biller_id null, company_name set — see user id 6 in the dump)
 * sign up through a public form. If your business is single-tenant and every
 * user is created by an admin, delete this component and route registration
 * through an admin-only "Add user" Livewire form instead.
 */
class Register extends Component
{
    public string $name = '';
    public string $company_name = '';
    public string $email = '';
    public string $phone = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:191'],
            'company_name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:191'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Owner-level role — adjust the lookup to match your seeded role name.
        $ownerRole = Role::where('name', 'Manager')->first()
            ?? Role::where('is_active', true)->orderBy('id')->first();

        $user = User::create([
            'name' => $this->name,
            'company_name' => $this->company_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => Hash::make($this->password),
            'role_id' => $ownerRole->id,
            'biller_id' => null,
            'warehouse_id' => null, // owner isn't scoped to a single warehouse
            'is_active' => 1,
            'is_deleted' => 0,
        ]);

        Auth::login($user);
        request()->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}

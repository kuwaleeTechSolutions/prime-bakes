<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login()
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $throttleKey = strtolower($this->email) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Too many attempts. Try again in {$seconds} seconds.",
            ]);
        }

        // Only ever authenticate against active, non-deleted accounts —
        // is_active/is_deleted are plain flags in this schema, not a real soft-delete.
        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
            'is_active' => 1,
            'is_deleted' => 0,
        ];

        if (! Auth::attempt($credentials, $this->remember)) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'email' => 'These credentials do not match an active account.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        request()->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Staff users are scoped to one warehouse/biller; owner-level users are not —
        // seed their session with whichever warehouse they should land on.
        if ($user->warehouse_id) {
            session(['active_warehouse_id' => $user->warehouse_id, 'active_warehouse' => $user->warehouse->name ?? null]);
        }

        return redirect()->intended(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}

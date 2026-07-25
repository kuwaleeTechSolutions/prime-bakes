<div>
    <h2 class="mb-1 text-lg font-semibold">Create your business account</h2>
    <p class="mb-6 text-sm text-text-secondary">You'll be able to add warehouses and staff after signing up</p>

    <form wire:submit="register" class="space-y-4">

        <div>
            <label for="name" class="field-label">Your name</label>
            <input id="name" type="text" wire:model="name" required autofocus
                   placeholder="Jane Doe" class="field-input">
            @error('name') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="company_name" class="field-label">Business name</label>
            <input id="company_name" type="text" wire:model="company_name"
                   placeholder="Your Store Pvt Ltd" class="field-input">
            @error('company_name') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="field-label">Email</label>
            <input id="email" type="email" wire:model="email"
                   placeholder="you@store.com" class="field-input">
            @error('email') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="phone" class="field-label">Phone</label>
            <input id="phone" type="text" wire:model="phone"
                   placeholder="9876543210" class="field-input">
            @error('phone') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="field-label">Password</label>
            <input id="password" type="password" wire:model="password"
                   placeholder="••••••••" class="field-input">
            @error('password') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="field-label">Confirm password</label>
            <input id="password_confirmation" type="password" wire:model="password_confirmation"
                   placeholder="••••••••" class="field-input">
        </div>

        <button type="submit" class="btn-primary w-full" wire:loading.attr="disabled" wire:target="register">
            <span wire:loading.remove wire:target="register">Create account</span>
            <span wire:loading wire:target="register">Creating account…</span>
        </button>

        <p class="text-center text-xs text-text-secondary">
            Already have an account?
            <a href="{{ route('login') }}" class="text-text-accent hover:underline">Sign in</a>
        </p>
    </form>
</div>

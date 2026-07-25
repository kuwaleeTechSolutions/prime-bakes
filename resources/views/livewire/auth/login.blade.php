<div>
    <h2 class="mb-1 text-lg font-semibold">Sign in</h2>
    <p class="mb-6 text-sm text-text-secondary">Enter your staff credentials</p>

    <form wire:submit="login" class="space-y-4">

        <div>
            <label for="email" class="field-label">Email</label>
            <input id="email" type="email" wire:model="email" required autofocus
                   placeholder="you@store.com" class="field-input">
            @error('email') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="field-label">Password</label>
            <input id="password" type="password" wire:model="password" required
                   placeholder="••••••••" class="field-input">
            @error('password') <p class="mt-1 text-xs text-status-unpaid">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between text-xs">
            <label class="flex items-center gap-2 text-text-secondary">
                <input type="checkbox" wire:model="remember" class="rounded border-border">
                Remember me
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-text-accent hover:underline">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn-primary w-full" wire:loading.attr="disabled" wire:target="login">
            <span wire:loading.remove wire:target="login">Sign in</span>
            <span wire:loading wire:target="login">Signing in…</span>
        </button>

        @if (Route::has('register'))
            <p class="text-center text-xs text-text-secondary">
                Setting up a new business?
                <a href="{{ route('register') }}" class="text-text-accent hover:underline">Create an account</a>
            </p>
        @endif
    </form>
</div>

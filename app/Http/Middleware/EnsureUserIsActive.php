<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * A user's is_active/is_deleted flags can change (e.g. an admin deactivates
 * a cashier) while that user still has a valid session. This middleware
 * force-logs them out on their next request instead of waiting for the
 * session to expire naturally.
 */
class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (! $user->is_active || $user->is_deleted) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account has been deactivated. Contact your administrator.']);
            }
        }

        return $next($request);
    }
}

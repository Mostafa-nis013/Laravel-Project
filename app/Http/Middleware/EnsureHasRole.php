<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasRole
{
    /**
     * Usage in routes:
     *   ->middleware('role:admin')
     *   ->middleware('role:admin,editor')   ← any of these roles passes
     *
     * @param string $roles  Comma-separated list of allowed role names.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        // Must be logged in first
        if (!$user) {
            return redirect()->route('login');
        }

        // Account must be active
        if (!$user->is_active) {
            Auth::logout();
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Your account has been deactivated.']);
        }

        // Check if the user holds at least one of the required roles
        if (!$user->hasRole($roles)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}

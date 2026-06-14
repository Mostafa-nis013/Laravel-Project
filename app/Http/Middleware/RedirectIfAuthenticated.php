<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Redirect already-authenticated users away from guest-only pages
     * (login, register) to their appropriate home.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                if ($user->isAdmin() || $user->hasRole(['editor', 'moderator'])) {
                    return redirect()->route('dashboard');
                }

                return redirect()->route('shop.index');
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ── Login ────────────────────────────────────────────────────────────────

    public function showLogin()
    {
        // Already logged-in users go straight to their destination
        if (Auth::check()) {
            return $this->redirectAfterLogin(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // Account exists but is deactivated
        if ($user && !$user->is_active) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Your account has been deactivated. Contact an administrator.']);
        }

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        $request->session()->regenerate();

        return $this->redirectAfterLogin(Auth::user());
    }

    // ── Register ─────────────────────────────────────────────────────────────

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectAfterLogin(Auth::user());
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // New registrations default to the 'user' role
        $userRole = Role::where('name', Role::USER)->first();
        if ($userRole) {
            $user->assignRole($userRole);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Welcome to Velour, ' . $user->name . '!');
    }

    // ── Logout ───────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Send the user to the right place after login based on their role.
     */
    private function redirectAfterLogin(User $user): \Illuminate\Http\RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->intended(route('dashboard'));
        }

        // Editors and moderators also get admin access
        if ($user->hasRole([Role::EDITOR, Role::MODERATOR])) {
            return redirect()->intended(route('dashboard'));
        }

        // Regular users land on the storefront home (or a placeholder)
        return redirect()->intended(route('home'));
    }
}

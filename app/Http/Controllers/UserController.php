<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->requirePermission('manage_users');
        $query = User::latest();

        if ($request->filled('role'))   $query->where('role', $request->role);
        if ($request->filled('status')) $query->where('is_active', $request->status === 'active');
        if ($request->filled('search')) {
            $query->where(fn($q) => $q->where('name', 'like', "%{$request->search}%")
                                      ->orWhere('email', 'like', "%{$request->search}%"));
        }

        $users = $query->paginate(10)->withQueryString();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $this->requirePermission('manage_users');
        return view('users.create', ['availableRoles' => $this->availableRoles()]);
    }

    public function store(Request $request)
    {
        $this->requirePermission('manage_users');
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => ['required', 'in:super_admin,admin,editor,user'],
            'is_active'=> ['boolean'],
        ]);

        // Only super_admin can create super_admin
        if ($data['role'] === 'super_admin' && ! Auth::user()->isSuperAdmin()) abort(403);

        User::create($data);
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $this->requirePermission('manage_users');
        return view('users.edit', ['user' => $user, 'availableRoles' => $this->availableRoles()]);
    }

    public function update(Request $request, User $user)
    {
        $this->requirePermission('manage_users');

        $rules = [
            'name'      => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email', 'unique:users,email,' . $user->id],
            'role'      => ['required', 'in:super_admin,admin,editor,user'],
            'is_active' => ['boolean'],
        ];
        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Password::min(8)];
        }

        $data = $request->validate($rules);
        if ($data['role'] === 'super_admin' && ! Auth::user()->isSuperAdmin()) abort(403);
        if (empty($data['password'])) unset($data['password']);

        $user->update($data);
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->requirePermission('manage_users');
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted.');
    }

    private function requirePermission(string $perm): void
    {
        if (! Auth::user()->hasPermission($perm)) abort(403);
    }

    private function availableRoles(): array
    {
        $roles = ['admin' => 'Admin', 'editor' => 'Editor', 'user' => 'User'];
        if (Auth::user()->isSuperAdmin()) $roles = ['super_admin' => 'Super Admin'] + $roles;
        return $roles;
    }
}

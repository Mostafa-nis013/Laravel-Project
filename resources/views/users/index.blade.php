@extends('layouts.app')
@section('title', 'Users')
@section('page-title', 'Users')

@section('topbar-actions')
    <a href="{{ route('users.create') }}" class="btn btn-primary">+ New User</a>
@endsection

@section('content')
<div class="card filter-card">
    <form method="GET" action="{{ route('users.index') }}" class="filter-form">
        <input type="text" name="search" class="form-input" placeholder="Search users…" value="{{ request('search') }}" />
        <select name="role" class="form-input">
            <option value="">All roles</option>
            <option value="super_admin" {{ request('role')=='super_admin'?'selected':'' }}>Super Admin</option>
            <option value="admin"       {{ request('role')=='admin'?'selected':'' }}>Admin</option>
            <option value="editor"      {{ request('role')=='editor'?'selected':'' }}>Editor</option>
            <option value="user"        {{ request('role')=='user'?'selected':'' }}>User</option>
        </select>
        <select name="status" class="form-input">
            <option value="">All statuses</option>
            <option value="active"   {{ request('status')=='active'?'selected':'' }}>Active</option>
            <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
        </select>
        <button type="submit" class="btn btn-secondary">Filter</button>
        @if(request()->hasAny(['search','role','status']))
            <a href="{{ route('users.index') }}" class="btn btn-ghost">Clear</a>
        @endif
    </form>
</div>

<div class="card">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Articles</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="mini-avatar" style="background: var(--color-indigo)">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span>{{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span class="badge badge-you">You</span>
                                @endif
                            </span>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge {{ $user->getRoleBadgeColor() }}">{{ $user->getRoleLabel() }}</span></td>
                    <td>
                        <span class="status-dot {{ $user->is_active ? 'active' : 'inactive' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ $user->articles_count ?? $user->articles()->count() }}</td>
                    <td>{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : '—' }}</td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-xs btn-secondary">✏️ Edit</a>
                            @if($user->id !== auth()->id())
                            <button class="btn btn-xs btn-danger delete-btn"
                                data-url="{{ route('users.destroy', $user) }}"
                                data-name="{{ $user->name }}">🗑</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="empty-row">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">{{ $users->links() }}</div>
</div>
@endsection

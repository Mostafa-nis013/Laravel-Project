@extends('layouts.admin')

@section('title', 'Users')
@section('topbar-title', 'Users & Roles')

@section('content')
<div class="page-header">
    <div>
        <h2>Users</h2>
        <p>{{ $users->total() }} registered accounts</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary">+ Add User</a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('users.index') }}">
    <div class="filters-bar">
        <input type="text" name="search" placeholder="Search by name or email…" value="{{ request('search') }}">
        <select name="role">
            <option value="">All Roles</option>
            @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ $role->label }}</option>
            @endforeach
        </select>
        <select name="status">
            <option value="">All Status</option>
            <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="btn btn-secondary">Filter</button>
        @if(request()->hasAny(['search', 'role', 'status']))
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Clear</a>
        @endif
    </div>
</form>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Roles</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px">
                            {{-- Avatar initial --}}
                            <div style="width:38px;height:38px;border-radius:50%;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-weight:600;font-size:0.9rem;color:var(--accent);flex-shrink:0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:500">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="badge badge-yellow" style="font-size:0.65rem;padding:1px 7px;margin-left:4px">You</span>
                                    @endif
                                </div>
                                <div style="font-size:0.78rem;color:var(--muted)">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;gap:5px;flex-wrap:wrap">
                            @forelse($user->roles as $role)
                                @php
                                    $roleColor = match($role->name) {
                                        'admin'     => 'badge-yellow',
                                        'editor'    => 'badge-blue',
                                        'moderator' => 'badge-purple',
                                        default     => 'badge-green',
                                    };
                                @endphp
                                <span class="badge {{ $roleColor }}">{{ $role->label }}</span>
                            @empty
                                <span style="color:var(--muted);font-size:0.82rem">No role</span>
                            @endforelse
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $user->is_active ? 'badge-green' : 'badge-red' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td style="color:var(--muted);font-size:0.85rem">{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('users.show', $user) }}" class="btn btn-secondary btn-sm">View</a>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-secondary btn-sm">Edit</a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('users.destroy', $user) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" data-confirm="Delete user '{{ $user->name }}'? This cannot be undone.">Delete</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:40px;color:var(--muted)">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="pagination">
        @if($users->onFirstPage())
            <span class="page-link" style="opacity:0.3">←</span>
        @else
            <a href="{{ $users->previousPageUrl() }}" class="page-link">←</a>
        @endif
        @foreach($users->getUrlRange(max(1,$users->currentPage()-2), min($users->lastPage(),$users->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}" class="page-link {{ $page == $users->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach
        @if($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}" class="page-link">→</a>
        @else
            <span class="page-link" style="opacity:0.3">→</span>
        @endif
        <span style="margin-left:auto;color:var(--muted);font-size:0.85rem">
            {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }}
        </span>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => { if (!confirm(el.dataset.confirm)) e.preventDefault(); });
});
</script>
@endpush

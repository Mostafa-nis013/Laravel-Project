@extends('layouts.admin')

@section('title', 'Edit User')
@section('topbar-title', 'Users / Edit')
@section('breadcrumb')
    <span style="color:var(--muted)">People</span>
    <span class="crumb-sep">›</span>
    <span class="crumb-current">Users / Edit</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Edit User</h2>
        <p>Editing: <strong>{{ $user->name }}</strong></p>
    </div>
    <div style="display:flex;gap:10px">
        <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">View</a>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">← Back</a>
    </div>
</div>

<form method="POST" action="{{ route('users.update', $user) }}">
    @csrf @method('PUT')
    <div class="grid-2" style="align-items:start">

        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header"><span class="card-title">Account Details</span></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Email Address <span class="required">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div style="border-top:1px solid var(--border);padding-top:16px;margin-top:8px">
                        <p style="font-size:0.8rem;color:var(--muted);margin-bottom:12px">Leave password fields blank to keep the current password.</p>
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="password" placeholder="Min. 8 characters">
                            @error('password')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Confirm New Password</label>
                            <input type="password" name="password_confirmation" placeholder="Repeat password">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">Account Status</span></div>
                <div class="card-body">
                    <div class="form-check">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        <label for="is_active">Active — user can log in</label>
                    </div>
                    @if($user->id === auth()->id())
                        <p style="margin-top:10px;font-size:0.8rem;color:var(--muted)">You cannot deactivate your own account.</p>
                    @endif
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header"><span class="card-title">Roles <span class="required">*</span></span></div>
                <div class="card-body">
                    @error('roles')<div class="form-error" style="margin-bottom:14px">{{ $message }}</div>@enderror

                    @php $userRoleIds = $user->roles->pluck('id')->toArray(); @endphp
                    <div style="display:flex;flex-direction:column;gap:2px">
                        @foreach($roles as $role)
                        @php
                            $colors = [
                                'admin'     => ['bg' => 'rgba(232,200,122,0.08)', 'border' => 'rgba(232,200,122,0.25)', 'text' => '#e8c87a'],
                                'editor'    => ['bg' => 'rgba(82,130,224,0.08)',  'border' => 'rgba(82,130,224,0.25)',  'text' => '#7aabf0'],
                                'moderator' => ['bg' => 'rgba(150,100,220,0.08)','border' => 'rgba(150,100,220,0.25)','text' => '#c090f0'],
                                'user'      => ['bg' => 'rgba(82,192,122,0.08)', 'border' => 'rgba(82,192,122,0.25)', 'text' => '#7ad4a0'],
                            ];
                            $c = $colors[$role->name] ?? ['bg'=>'var(--surface2)','border'=>'var(--border)','text'=>'var(--text)'];
                            $checked = in_array($role->id, old('roles', $userRoleIds));
                        @endphp
                        <label style="display:flex;align-items:flex-start;gap:12px;padding:14px 16px;border-radius:var(--radius);border:1px solid {{ $c['border'] }};background:{{ $c['bg'] }};cursor:pointer;margin-bottom:8px">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                {{ $checked ? 'checked' : '' }}
                                style="margin-top:2px;accent-color:{{ $c['text'] }};width:16px;height:16px;flex-shrink:0">
                            <div>
                                <div style="font-weight:600;color:{{ $c['text'] }};margin-bottom:3px">{{ $role->label }}</div>
                                <div style="font-size:0.8rem;color:var(--muted)">{{ $role->description }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            @if($user->id !== auth()->id())
            <div class="card" style="border-color:rgba(224,82,82,0.3)">
                <div class="card-header"><span class="card-title" style="color:var(--danger)">Danger Zone</span></div>
                <div class="card-body">
                    <p style="font-size:0.875rem;color:var(--muted);margin-bottom:14px">Permanently delete this user and remove all their roles.</p>
                    <form method="POST" action="{{ route('users.destroy', $user) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" data-confirm="Permanently delete '{{ $user->name }}'?">Delete User</button>
                    </form>
                </div>
            </div>
            @endif

            <div style="display:flex;gap:12px;justify-content:flex-end">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => { if (!confirm(el.dataset.confirm)) e.preventDefault(); });
});
</script>
@endpush

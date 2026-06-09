@extends('layouts.admin')

@section('title', 'Add User')
@section('topbar-title', 'Users / Add New')

@section('content')
<div class="page-header">
    <div>
        <h2>Add User</h2>
        <p>Create a new account and assign roles.</p>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">← Back</a>
</div>

<form method="POST" action="{{ route('users.store') }}">
    @csrf
    <div class="grid-2" style="align-items:start">

        {{-- Left: account details --}}
        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header"><span class="card-title">Account Details</span></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Jane Smith">
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Email Address <span class="required">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="jane@example.com">
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Password <span class="required">*</span></label>
                        <input type="password" name="password" placeholder="Min. 8 characters" required>
                        @error('password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Confirm Password <span class="required">*</span></label>
                        <input type="password" name="password_confirmation" placeholder="Repeat password" required>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">Account Status</span></div>
                <div class="card-body">
                    <div class="form-check">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active">Active — user can log in</label>
                    </div>
                    <p style="margin-top:10px;font-size:0.8rem;color:var(--muted)">Deactivated users are blocked from signing in.</p>
                </div>
            </div>
        </div>

        {{-- Right: roles --}}
        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Assign Roles <span class="required">*</span></span>
                </div>
                <div class="card-body">
                    @error('roles')<div class="form-error" style="margin-bottom:14px">{{ $message }}</div>@enderror

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
                        @endphp
                        <label style="display:flex;align-items:flex-start;gap:12px;padding:14px 16px;border-radius:var(--radius);border:1px solid {{ $c['border'] }};background:{{ $c['bg'] }};cursor:pointer;margin-bottom:8px;transition:opacity 0.15s">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}
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

            <div style="display:flex;gap:12px;justify-content:flex-end">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create User</button>
            </div>
        </div>
    </div>
</form>
@endsection

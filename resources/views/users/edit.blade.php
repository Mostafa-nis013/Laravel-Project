@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="form-page form-page-narrow">
    <div class="card">
        <div class="user-edit-header">
            <div class="user-avatar-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div>
                <div class="user-name-lg">{{ $user->name }}</div>
                <span class="badge {{ $user->getRoleBadgeColor() }}">{{ $user->getRoleLabel() }}</span>
                <div class="muted">Joined {{ $user->created_at->format('M d, Y') }}</div>
            </div>
        </div>

        <form id="userForm" method="POST" action="{{ route('users.update', $user) }}" novalidate>
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label" for="name">Full Name <span class="required">*</span></label>
                <input id="name" type="text" name="name" class="form-input @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name) }}" />
                @error('name')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email <span class="required">*</span></label>
                <input id="email" type="email" name="email" class="form-input @error('email') is-invalid @enderror"
                       value="{{ old('email', $user->email) }}" />
                @error('email')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="role">Role <span class="required">*</span></label>
                <select id="role" name="role" class="form-input">
                    @foreach($availableRoles as $value => $label)
                        <option value="{{ $value }}" {{ old('role', $user->role)===$value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">New Password <span class="muted">(leave blank to keep)</span></label>
                <div class="input-wrapper">
                    <input id="password" type="password" name="password"
                           class="form-input @error('password') is-invalid @enderror"
                           placeholder="Min. 8 characters" />
                    <button type="button" class="toggle-password" data-target="password">👁</button>
                </div>
                @error('password')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm New Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="form-input" placeholder="Repeat new password" />
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                    Account Active
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('users.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

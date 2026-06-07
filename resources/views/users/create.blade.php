@extends('layouts.app')
@section('title', 'New User')
@section('page-title', 'New User')

@section('content')
<div class="form-page form-page-narrow">
    <div class="card">
        <form id="userForm" method="POST" action="{{ route('users.store') }}" novalidate>
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">Full Name <span class="required">*</span></label>
                <input id="name" type="text" name="name" class="form-input @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" placeholder="Jane Doe" />
                @error('name')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email <span class="required">*</span></label>
                <input id="email" type="email" name="email" class="form-input @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="jane@example.com" />
                @error('email')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="role">Role <span class="required">*</span></label>
                <select id="role" name="role" class="form-input @error('role') is-invalid @enderror">
                    @foreach($availableRoles as $value => $label)
                        <option value="{{ $value }}" {{ old('role')===$value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('role')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password <span class="required">*</span></label>
                <div class="input-wrapper">
                    <input id="password" type="password" name="password"
                           class="form-input @error('password') is-invalid @enderror"
                           placeholder="Min. 8 characters" />
                    <button type="button" class="toggle-password" data-target="password">👁</button>
                </div>
                @error('password')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm Password <span class="required">*</span></label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="form-input" placeholder="Repeat password" />
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', '1') ? 'checked' : '' }}>
                    Account Active
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create User</button>
                <a href="{{ route('users.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

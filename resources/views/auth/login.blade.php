@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="auth-card">
    <h2 class="auth-title">Welcome back</h2>
    <p class="auth-subtitle">Sign in to your account</p>

    <form id="loginForm" method="POST" action="{{ route('login.post') }}" novalidate>
        @csrf

        <div class="form-group">
            <label class="form-label" for="email">Email address</label>
            <input id="email" type="email" name="email" class="form-input @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" />
            @error('email')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="input-wrapper">
                <input id="password" type="password" name="password" class="form-input @error('password') is-invalid @enderror"
                       placeholder="••••••••" autocomplete="current-password" />
                <button type="button" class="toggle-password" data-target="password">👁</button>
            </div>
            @error('password')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-row">
            <label class="checkbox-label">
                <input type="checkbox" name="remember"> Remember me
            </label>
        </div>

        <button type="submit" class="btn btn-primary btn-full" id="loginBtn">
            <span class="btn-text">Sign In</span>
            <span class="btn-spinner hidden">⏳</span>
        </button>
    </form>

    <p class="auth-link">Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>

    <div class="demo-accounts">
        <p class="demo-title">Demo Accounts <span>(password: password)</span></p>
        <div class="demo-grid">
            <button class="demo-btn" data-email="superadmin@demo.com">Super Admin</button>
            <button class="demo-btn" data-email="admin@demo.com">Admin</button>
            <button class="demo-btn" data-email="editor@demo.com">Editor</button>
            <button class="demo-btn" data-email="user@demo.com">User</button>
        </div>
    </div>
</div>
@endsection

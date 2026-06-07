@extends('layouts.auth')
@section('title', 'Register')

@section('content')
<div class="auth-card">
    <h2 class="auth-title">Create account</h2>
    <p class="auth-subtitle">Join LaravelCMS today</p>

    <form id="registerForm" method="POST" action="{{ route('register.post') }}" novalidate>
        @csrf

        <div class="form-group">
            <label class="form-label" for="name">Full Name</label>
            <input id="name" type="text" name="name" class="form-input @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" placeholder="John Doe" />
            @error('name')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Email address</label>
            <input id="email" type="email" name="email" class="form-input @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="you@example.com" />
            @error('email')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="input-wrapper">
                <input id="password" type="password" name="password" class="form-input @error('password') is-invalid @enderror"
                       placeholder="Min. 8 characters" />
                <button type="button" class="toggle-password" data-target="password">👁</button>
            </div>
            @error('password')<span class="form-error">{{ $message }}</span>@enderror
            <div class="password-strength" id="pwStrength"><div class="strength-bar"></div></div>
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-input" placeholder="Repeat password" />
        </div>

        <button type="submit" class="btn btn-primary btn-full">Create Account</button>
    </form>

    <p class="auth-link">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
</div>
@endsection

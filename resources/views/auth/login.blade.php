@extends('layouts.guest')

@section('title', 'Login')
@section('subtitle', 'Sign in to your account')

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf

    <!-- Email -->
    <div class="form-group">
        <label for="email" class="form-label">
            Email Address <span class="required">*</span>
        </label>
        <input
            type="email"
            id="email"
            name="email"
            class="form-input"
            value="{{ old('email') }}"
            placeholder="Enter your email"
            required
            autofocus
        >
    </div>

    <!-- Password -->
    <div class="form-group">
        <label for="password" class="form-label">
            Password <span class="required">*</span>
        </label>
        <input
            type="password"
            id="password"
            name="password"
            class="form-input"
            placeholder="Enter your password"
            required
        >
    </div>

    <!-- Remember Me -->
    <div class="form-checkbox-group">
        <input
            type="checkbox"
            id="remember"
            name="remember"
            class="form-checkbox"
        >
        <label for="remember" class="form-checkbox-label">
            Remember me
        </label>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">
        Sign In
    </button>
</form>

@if($errors->has('email') && str_contains($errors->first('email'), 'verify'))
<div class="alert alert-error">
    <div class="alert-icon">
        <svg fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
    </div>
    <div class="alert-text">
        <p>{{ $errors->first('email') }}</p>
        <form method="POST" action="{{ route('verification.resend') }}" style="margin-top: 0.5rem;">
            @csrf
            <input type="hidden" name="email" value="{{ old('email') }}">
            <button type="submit" style="color: #3b82f6; text-decoration: underline; background: none; border: none; cursor: pointer; padding: 0; font-size: 0.875rem;">
                Resend verification email
            </button>
        </form>
    </div>
</div>
@endif
@endsection

@section('footer')
<p>
    Don't have an account? 
    <a href="{{ route('register') }}">Register here</a>
</p>
<p>
    <a href="{{ route('password.request') }}">Forgot your password?</a>
</p>
@endsection
@extends('layouts.guest')

@section('title', 'Reset Password')
@section('subtitle', 'Enter your new password')

@section('content')
<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ $token }}">

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
            value="{{ old('email', $email ?? '') }}"
            placeholder="Enter your email"
            required
            autofocus
        >
    </div>

    <!-- Password -->
    <div class="form-group">
        <label for="password" class="form-label">
            New Password <span class="required">*</span>
        </label>
        <input
            type="password"
            id="password"
            name="password"
            class="form-input"
            placeholder="Enter new password (min 8 characters)"
            required
        >
        <p class="form-help">Minimum 8 characters required</p>
    </div>

    <!-- Confirm Password -->
    <div class="form-group">
        <label for="password_confirmation" class="form-label">
            Confirm New Password <span class="required">*</span>
        </label>
        <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            class="form-input"
            placeholder="Re-enter your new password"
            required
        >
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">
        Reset Password
    </button>
</form>
@endsection

@section('footer')
<p>
    Remember your password? 
    <a href="{{ route('login') }}">Sign in here</a>
</p>
@endsection
@extends('layouts.guest')

@section('title', 'Forgot Password')
@section('subtitle', 'Reset your password')

@section('content')
<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <p style="margin-bottom: 1.5rem; color: #6b7280; font-size: 0.875rem;">
        Enter your email address and we'll send you a link to reset your password.
    </p>

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

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">
        Send Reset Link
    </button>
</form>
@endsection

@section('footer')
<p>
    Remember your password? 
    <a href="{{ route('login') }}">Sign in here</a>
</p>
@endsection
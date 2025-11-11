@extends('layouts.guest')

@section('title', 'Register')
@section('subtitle', 'Create your account')

@section('content')
<form method="POST" action="{{ route('register') }}">
    @csrf

    <!-- Name -->
    <div class="form-group">
        <label for="name" class="form-label">
            Full Name <span class="required">*</span>
        </label>
        <input
            type="text"
            id="name"
            name="name"
            class="form-input"
            value="{{ old('name') }}"
            placeholder="Enter your full name"
            required
            autofocus
        >
    </div>

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
            placeholder="Enter password (min 8 characters)"
            required
        >
        <p class="form-help">Minimum 8 characters required</p>
    </div>

    <!-- Confirm Password -->
    <div class="form-group">
        <label for="password_confirmation" class="form-label">
            Confirm Password <span class="required">*</span>
        </label>
        <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            class="form-input"
            placeholder="Re-enter your password"
            required
        >
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">
        Create Account
    </button>
</form>
@endsection

@section('footer')
<p>
    Already have an account? 
    <a href="{{ route('login') }}">Sign in here</a>
</p>
@endsection
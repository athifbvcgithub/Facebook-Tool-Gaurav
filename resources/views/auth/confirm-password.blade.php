@extends('layouts.guest')

@section('title', 'Confirm Password')
@section('subtitle', 'Please confirm your password to continue')

@section('content')
<form method="POST" action="{{ route('password.confirm') }}">
    @csrf

    <p style="margin-bottom: 1.5rem; color: #6b7280; font-size: 0.875rem;">
        This is a secure area of the application. Please confirm your password before continuing.
    </p>

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
            autofocus
        >
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">
        Confirm
    </button>
</form>
@endsection

@section('footer')
<p>
    <a href="{{ route('password.request') }}">Forgot your password?</a>
</p>
@endsection
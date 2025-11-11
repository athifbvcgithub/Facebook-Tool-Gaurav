@extends('layouts.guest')

@section('title', 'Verify Email')
@section('subtitle', 'Email verification required')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem;">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
    </p>
    <p style="color: #6b7280; font-size: 0.875rem;">
        If you didn't receive the email, we will gladly send you another.
    </p>
</div>

@if (session('status') == 'verification-link-sent')
<div class="alert alert-success">
    <div class="alert-icon">
        <svg fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
    </div>
    <div class="alert-text">
        <p>A new verification link has been sent to your email address.</p>
    </div>
</div>
@endif

<div style="display: flex; gap: 1rem; align-items: center; justify-content: space-between;">
    <form method="POST" action="{{ route('verification.send') }}" style="flex: 1;">
        @csrf
        <button type="submit" class="btn btn-primary">
            Resend Verification Email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" style="padding: 0.75rem 1rem; background: none; border: 1px solid #d1d5db; border-radius: 0.5rem; color: #6b7280; cursor: pointer; font-size: 0.875rem; font-weight: 500;">
            Log Out
        </button>
    </form>
</div>
@endsection
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name') }}</title>
    
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Auth CSS -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    
    @stack('styles')
</head>
<body>
    <div class="auth-container">
        <!-- Header -->
        <div class="auth-header">
            <h1>{{ config('app.name') }}</h1>
            <p>@yield('subtitle')</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="alert alert-success">
            <div class="alert-icon">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="alert-text">
                <p>{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
        <div class="alert alert-error">
            <div class="alert-icon">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="alert-text">
                @if($errors->count() === 1)
                    <p>{{ $errors->first() }}</p>
                @else
                    <p><strong>Please fix the following errors:</strong></p>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <div class="auth-card">
            @yield('content')
        </div>

        <!-- Footer Links -->
        <div class="auth-footer">
            @yield('footer')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
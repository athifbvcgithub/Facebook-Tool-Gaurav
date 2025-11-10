<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Facebook Ads Tool') }}</title>
    
    <!-- Main CSS - Remove Tailwind CDN -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <div class="header-content">
                <!-- Left Side -->
                <div class="header-left">
                    <!-- Logo -->
                    <a href="{{ route('dashboard') }}" class="logo">
                        FaceBook-Ads
                    </a>

                    <!-- Main Navigation -->
                    <nav class="main-nav">
                        <a href="{{ route('dashboard') }}" 
                           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('stats') }}" 
                           class="nav-link {{ request()->routeIs('stats') ? 'active' : '' }}">
                            Stats
                        </a>
                        <a href="{{ route('ads.index') }}" 
                           class="nav-link {{ request()->routeIs('ads.*') || request()->routeIs('campaigns.*') || request()->routeIs('adsets.*') ? 'active' : '' }}">
                            Ads
                        </a>
                        <a href="{{ route('benchmarks') }}" 
                           class="nav-link {{ request()->routeIs('benchmarks') ? 'active' : '' }}">
                            Benchmarks
                        </a>
                        <a href="{{ route('notifications') }}" 
                           class="nav-link {{ request()->routeIs('notifications') ? 'active' : '' }}">
                            Notifications
                        </a>
                    </nav>
                </div>

                <!-- Right Side -->
                <div class="header-right">
                    <span class="user-email">
                        {{ auth()->user()->email ?? 'athif.hussain@bvceservices.com' }}
                    </span>
                    
                    <!-- Icons -->
                    <button class="icon-button">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </button>

                    <button class="icon-button">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </button>

                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="icon-button">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    @yield('content')

    @stack('scripts')
</body>
</html>
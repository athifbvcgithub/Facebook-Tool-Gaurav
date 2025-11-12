<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
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
                        {{ auth()->user()?->email ?? '' }}
                    </span>
                    
                    <!-- setting Icons -->
                    <button class="icon-button" onclick="toggleProfileDropdown()">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </button>

                    <!-- Profile Dropdown (optional) -->
                    <div id="profileDropdown" style="display: none; position: absolute; right: 1rem; top: 4rem; background: white; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 0.5rem; min-width: 200px; z-index: 50;">
                        <div style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">
                            <p style="font-weight: 600; margin: 0;">{{ Auth::user()?->name }}</p>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0.25rem 0 0 0;">{{ Auth::user()?->email }}</p>
                        </div>
                        <div style="padding: 0.5rem 0;">
                            <a href="{{ route('profile.show') }}" style="display: block; padding: 0.5rem 0.75rem; color: #374151; text-decoration: none; border-radius: 0.375rem; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='transparent'">
                                Profile Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                @csrf
                                <button type="submit" style="width: 100%; text-align: left; padding: 0.5rem 0.75rem; background: none; border: none; color: #ef4444; cursor: pointer; border-radius: 0.375rem; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#fef2f2'" onmouseout="this.style.backgroundColor='transparent'">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    @yield('content')

    @stack('scripts')

    <script>
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const button = event.target.closest('.icon-button');
            if (!button && dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
            }
        });
        </script>

    <script>
    // Prevent back button after logout
    window.onload = function() {
        // Check if user is authenticated
        @guest
            // If not authenticated, prevent going back to protected pages
            window.history.forward();
            
            // Disable back button
            function preventBack() {
                window.history.forward();
            }
            setTimeout("preventBack()", 0);
            window.onunload = function() { null };
        @endguest
    };

    // Clear cache on page unload
    window.addEventListener('beforeunload', function() {
        @guest
            // Clear any cached data
            if (typeof(Storage) !== "undefined") {
                sessionStorage.clear();
            }
        @endguest
    });
    </script>

</body>
</html>
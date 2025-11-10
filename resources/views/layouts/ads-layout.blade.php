@extends('layouts.app')

<style>
/* Alert Base Styles */
.alert {
    display: flex;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
}

.alert-icon {
    flex-shrink: 0;
    width: 1.25rem;
    height: 1.25rem;
    margin-right: 0.75rem;
}

.alert-content {
    flex: 1;
}

.alert-message {
    font-size: 0.875rem;
    line-height: 1.5;
}

/* Success Alert */
.alert-success {
    background-color: #d1fae5;
    border: 1px solid #6ee7b7;
    color: #065f46;
}

.alert-success .alert-icon {
    color: #059669;
}

/* Error Alert */
.alert-error {
    background-color: #fee2e2;
    border: 1px solid #fca5a5;
    color: #991b1b;
}

.alert-error .alert-icon {
    color: #dc2626;
}

/* Warning Alert (optional) */
.alert-warning {
    background-color: #fef3c7;
    border: 1px solid #fcd34d;
    color: #92400e;
}

.alert-warning .alert-icon {
    color: #f59e0b;
}
</style>

@section('content')
<!-- Sub Navigation -->
<div class="sub-nav">
    <div class="sub-nav-container">
        <nav class="tabs">
            <a href="{{ route('ads.index') }}" 
               class="tab-link {{ request()->routeIs('ads.index') ? 'active' : '' }}">
                Ads
            </a>
            <a href="{{ route('ads.launcher') }}" 
               class="tab-link {{ request()->routeIs('ads.launcher') ? 'active' : '' }}">
                Ad Launcher
            </a>
            <a href="{{ route('ads.creatives') }}" 
               class="tab-link {{ request()->routeIs('ads.creatives') ? 'active' : '' }}">
                Creatives
            </a>
            <a href="{{ route('ads.launcher-presets') }}" 
               class="tab-link {{ request()->routeIs('ads.launcher-presets') ? 'active' : '' }}">
                Ad Launcher Presets
            </a>
            <a href="{{ route('ads.country-presets') }}" 
               class="tab-link {{ request()->routeIs('ads.country-presets') ? 'active' : '' }}">
                Country Presets
            </a>
            <!-- <a href="{{ route('campaigns.create') }}" 
               class="tab-link {{ request()->routeIs('campaigns.create') ? 'active' : '' }}">
                Add Campaigns
            </a>
            <a href="{{ route('adsets.create') }}" 
               class="tab-link {{ request()->routeIs('adsets.create') ? 'active' : '' }}">
                Add AdSet
            </a> -->
        </nav>
    </div>
</div>

<!-- Page Content -->
<div class="main-content">
    @yield('ads-content')
</div>
@endsection
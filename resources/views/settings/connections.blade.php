@extends('layouts.settings')

@section('title', 'Connections')

@section('settings-content')

<!-- Settings Content -->
<div class="settings-content">
    <h1 class="settings-title">Connections</h1>

    <!-- Search Box - Full Width -->
    <div class="search-box">
        <input 
            type="text" 
            class="search-input" 
            placeholder="Search" 
            id="searchInput"
        >
        <button type="button" class="search-btn" onclick="performSearch()">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </button>
    </div>

    <!-- Connections Grid -->
    <div class="connections-grid">
        <!-- Dynamic Connections from Database -->
        @forelse($connections as $connection)
        <div class="connection-card" style="cursor: pointer; transition: all 0.3s ease;"
     onclick="window.location.href='{{ route('settings.facebook-ad-accounts', $connection['id']) }}'"
     onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'"
     onmouseout="this.style.boxShadow=''">
            @if($connection['profile_picture'])
                <img 
                    src="{{ $connection['profile_picture'] }}" 
                    alt="{{ $connection['name'] }}" 
                    class="connection-logo"
                    style="border-radius: 50%;"
                >
            @else
                <img 
                    src="{{ asset('images/facebook_logo.png') }}" 
                    alt="Facebook" 
                    class="connection-logo"
                >
            @endif
            <div class="connection-name">Facebook - {{ $connection['name'] }}</div>
            <div class="connection-details">{{ $connection['name'] }}</div>
            <div class="connection-details">{{ $connection['facebook_user_id'] }}</div>
            @if($connection['ad_accounts_count'] > 0)
                <div class="connection-badge" style="margin-top: 10px;">
                    <span style="background: #0866ff; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px;">
                        {{ $connection['ad_accounts_count'] }} Ad Account(s)
                    </span>
                </div>
            @endif
        </div>
        @empty
        <!-- No connections message -->
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
            <p style="color: #666;">No Facebook accounts connected yet.</p>
        </div>
        @endforelse

        <!-- Add New Card -->
        <div class="connection-card add-new" onclick="window.location='{{ route('facebook.redirect') }}'">
            <div class="add-icon">+</div>
            <div class="add-text">Add new...</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.connection-card:not(.add-new)');
    
    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(searchTerm) ? 'block' : 'none';
    });
});
</script>
@endpush
@endsection
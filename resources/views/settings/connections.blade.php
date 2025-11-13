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
        <!-- Existing Connection -->
        <div class="connection-card">
            <img 
                src="{{ asset('images/facebook_logo.png') }}" 
                alt="Facebook" 
                class="connection-logo"
            >
            <div class="connection-name">Facebook - Athif Hussain</div>
            <div class="connection-details">Athif Hussain</div>
            <div class="connection-details">9773651176057183</div>
        </div>

        <!-- Add New Card -->
        <div class="connection-card add-new" onclick="window.location='{{ route('settings.connections.add') }}'">
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
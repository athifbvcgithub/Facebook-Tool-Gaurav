@extends('layouts.settings')

@section('title', 'Facebook Ad Accounts - ' . $accountInfo['name'])

@section('settings-content')

<!-- Back button and Header -->
<div style="margin-bottom: 2rem;">
    <a href="{{ route('settings.connections') }}" style="display: inline-flex; align-items: center; color: #0866ff; text-decoration: none; margin-bottom: 1rem;">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 0.5rem;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Connections
    </a>
    
    <div style="display: flex; align-items: center; gap: 1rem;">
        @if($accountInfo['profile_picture'])
            <img src="{{ $accountInfo['profile_picture'] }}" style="width: 50px; height: 50px; border-radius: 50%;">
        @else
            <img src="{{ asset('images/facebook_logo.png') }}" style="width: 50px; height: 50px;">
        @endif
        <div>
            <h1 class="settings-title" style="margin: 0;">{{ $accountInfo['name'] }}'s Ad Accounts</h1>
            <p style="color: #6b7280; margin: 0;">Facebook ID: {{ $accountInfo['facebook_user_id'] }}</p>
        </div>
    </div>
</div>

<!-- Search Box -->
<div class="search-box">
    <input 
        type="text" 
        class="search-input" 
        placeholder="Search ad accounts..." 
        id="searchInput"
    >
    <button type="button" class="search-btn" onclick="performSearch()">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </button>
</div>

<!-- Ad Accounts Table -->
<div style="background: white; border-radius: 0.5rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-top: 1.5rem;">
    @if(count($adAccounts) > 0)
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.875rem;">ID</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.875rem;">Name</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.875rem;">Currency</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.875rem;">Timezone</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.875rem;">Status</th>
                <!-- <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; font-size: 0.875rem;">Actions</th> -->
            </tr>
        </thead>
        <tbody>
            @foreach($adAccounts as $adAccount)
            <tr style="border-bottom: 1px solid #e5e7eb;" class="ad-account-row">
                <td style="padding: 1rem; color: #6b7280; font-size: 0.875rem;">
                    {{ $adAccount['ad_account_id'] }}
                </td>
                <td style="padding: 1rem; color: #1f2937; font-weight: 500;">
                    {{ $adAccount['account_name'] }}
                </td>
                <td style="padding: 1rem; color: #6b7280;">
                    {{ $adAccount['currency'] }}
                </td>
                <td style="padding: 1rem; color: #6b7280;">
                    {{ $adAccount['timezone_name'] ?? 'Asia/Calcutta' }}
                </td>
                <td style="padding: 1rem;">
                    @if($adAccount['is_active'])
                        <span style="color: #059669; font-size: 1.25rem;">?</span>
                    @else
                        <span style="color: #dc2626; font-size: 1.25rem;">?</span>
                    @endif
                </td>
                <!-- <td style="padding: 1rem;">
                    <div style="display: flex; gap: 0.5rem; justify-content: center;">
                        <button style="padding: 0.5rem 1rem; background: #0866ff; color: white; border: none; border-radius: 0.375rem; cursor: pointer;"
                                onclick="createCampaign('{{ $adAccount['ad_account_id'] }}')">
                            Create Campaign
                        </button>
                    </div>
                </td> -->
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="padding: 3rem; text-align: center;">
        <p style="color: #6b7280;">No ad accounts found for this Facebook account.</p>
    </div>
    @endif
</div>

@push('scripts')
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.ad-account-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

function createCampaign(adAccountId) {
    window.location.href = `/campaigns/create?ad_account=${adAccountId}`;
}
</script>
@endpush
@endsection
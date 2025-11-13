@extends('layouts.settings')

@section('title', 'Ad Accounts')

@section('settings-content')
<h1 class="settings-title">Ad accounts</h1>

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

<!-- Ad Accounts Table -->
<div style="background: white; border-radius: 0.5rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    @if(count($adAccounts) > 0)
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.875rem;">ID</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.875rem;">Name</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.875rem;">Provider</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.875rem;">Timezone</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.875rem;">Currency</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.875rem;">Status</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.875rem;">Created</th>
                <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; font-size: 0.875rem;">Actions</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @foreach($adAccounts as $adAccount)
            <tr style="border-bottom: 1px solid #e5e7eb;" class="ad-account-row">
                <td style="padding: 1rem; color: #6b7280; font-size: 0.875rem;">
                    {{ $adAccount['ad_account_id'] }}
                </td>
                <td style="padding: 1rem; color: #1f2937; font-weight: 500;">
                    {{ $adAccount['account_name'] }}
                </td>
                <td style="padding: 1rem; color: #6b7280;">
                    <div style="display: flex; align-items: center;">
                        @if($adAccount['facebook_account']['profile_picture'])
                            <img src="{{ $adAccount['facebook_account']['profile_picture'] }}" 
                                 style="width: 20px; height: 20px; border-radius: 50%; margin-right: 6px;">
                        @else
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#1877f2" style="margin-right: 6px;">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        @endif
                        Facebook
                    </div>
                </td>
                <td style="padding: 1rem; color: #6b7280;">
                    {{ $adAccount['timezone_name'] ?? 'Asia/Calcutta' }}
                </td>
                <td style="padding: 1rem; color: #6b7280;">
                    {{ $adAccount['currency'] }}
                </td>
                <td style="padding: 1rem;">
                    @if($adAccount['is_active'])
                        <span style="color: #059669; font-size: 1.25rem;">✓</span>
                    @else
                        <span style="color: #dc2626; font-size: 1.25rem;">✗</span>
                    @endif
                </td>
                <td style="padding: 1rem; color: #6b7280; font-size: 0.875rem;">
                    {{ \Carbon\Carbon::parse($adAccount['created_at'] ?? now())->format('Y-m-d H:i') }}
                </td>
                <td style="padding: 1rem;">
                    <div style="display: flex; gap: 0.5rem; justify-content: center;">
                        <!-- Edit Button -->
                        <button style="padding: 0.5rem; background: #374151; color: white; border: none; border-radius: 0.375rem; cursor: pointer;" 
                                title="Edit" 
                                onclick="editAdAccount('{{ $adAccount['ad_account_id'] }}')">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                        </button>
                        
                        <!-- Settings Button -->
                        <button style="padding: 0.5rem; background: #374151; color: white; border: none; border-radius: 0.375rem; cursor: pointer;" 
                                title="Settings"
                                onclick="openSettings('{{ $adAccount['ad_account_id'] }}')">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                            </svg>
                        </button>
                        
                        <!-- Delete Button -->
                        <button style="padding: 0.5rem; background: #ef4444; color: white; border: none; border-radius: 0.375rem; cursor: pointer;" 
                                title="Delete"
                                onclick="deleteAdAccount('{{ $adAccount['ad_account_id'] }}')">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Pagination (if needed in future) -->
    <div style="padding: 1rem; display: flex; justify-content: center; border-top: 1px solid #e5e7eb;">
        <button style="padding: 0.5rem 1rem; background: #374151; color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 500;">
            1
        </button>
    </div>
    @else
    <!-- No Data Message -->
    <div style="padding: 3rem; text-align: center;">
        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 1rem; color: #9ca3af;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
        <h3 style="margin-bottom: 0.5rem; color: #374151; font-weight: 600;">No Ad Accounts Found</h3>
        <p style="color: #6b7280; margin-bottom: 1rem;">Connect a Facebook account to see your ad accounts here.</p>
        <button onclick="window.location='{{ route('facebook.redirect') }}'" 
                style="padding: 0.5rem 1rem; background: #1877f2; color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 500;">
            Connect Facebook Account
        </button>
    </div>
    @endif
</div>

@push('scripts')
<script>
// Search functionality
function performSearch() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    filterTable(searchTerm);
}

// Real-time search on input
document.getElementById('searchInput').addEventListener('input', function(e) {
    filterTable(e.target.value.toLowerCase());
});

// Enter key to search
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        performSearch();
    }
});

function filterTable(searchTerm) {
    const rows = document.querySelectorAll('.ad-account-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
}

// Action functions
function editAdAccount(adAccountId) {
    // Implement edit functionality
    console.log('Edit ad account:', adAccountId);
    // window.location.href = `/ad-accounts/${adAccountId}/edit`;
}

function openSettings(adAccountId) {
    // Implement settings functionality
    console.log('Open settings for:', adAccountId);
    // window.location.href = `/ad-accounts/${adAccountId}/settings`;
}

function deleteAdAccount(adAccountId) {
    if(confirm('Are you sure you want to delete this ad account?')) {
        console.log('Delete ad account:', adAccountId);
        // Implement delete functionality
        // You can make an AJAX call here to delete the account
    }
}
</script>
@endpush
@endsection
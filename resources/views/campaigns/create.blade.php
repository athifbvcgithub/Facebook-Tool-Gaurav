@extends('layouts.ads-layout')

@section('ads-content')
<!-- Page Title with Back Button -->
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
    <h1 class="page-title" style="margin-bottom: 0;">Create Campaign</h1>
    <a href="{{ url()->previous() }}" class="btn btn-secondary">← Back</a>
</div>

<!-- Success/Error Message -->
@if(session('success'))
<div class="alert alert-success">
    <div class="alert-icon">
        <svg fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
    </div>
    <div class="alert-content">
        <p class="alert-message">{{ session('success') }}</p>
    </div>
</div>
@endif

@if(session('error'))
<div class="alert alert-error">
    <div class="alert-icon">
        <svg fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
    </div>
    <div class="alert-content">
        <p class="alert-message">{{ session('error') }}</p>
    </div>
</div>
@endif

@if($errors->any())
<div class="alert alert-error" style="margin-bottom: 2rem;">
    <div class="alert-icon">
        <svg fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
    </div>
    <div class="alert-content">
        <p class="alert-message"><strong>Please fix the following errors:</strong></p>
        <ul style="margin-top: 0.5rem; margin-left: 1.5rem; list-style: disc;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<!-- Form Card -->
<div class="card">
    <div style="padding: 2rem;">
        <form method="POST" action="{{ route('campaigns.store') }}" id="campaignForm">
            @csrf

            <!-- Campaign Name -->
            <div style="margin-bottom: 1.5rem;">
                <label for="name" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    Campaign Name <span style="color: #ef4444;">*</span>
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter campaign name"
                    required
                    style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                >
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                    Choose a descriptive name for your campaign
                </p>
            </div>

            <!-- Objective -->
            <div style="margin-bottom: 1.5rem;">
                <label for="objective" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    Objective <span style="color: #ef4444;">*</span>
                </label>
                <select
                    id="objective"
                    name="objective"
                    required
                    style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: white; cursor: pointer;"
                >
                    <option value="">Select objective</option>
                    <option value="OUTCOME_LEADS" {{ old('objective') == 'OUTCOME_LEADS' ? 'selected' : '' }}>Leads</option>
                    <option value="OUTCOME_SALES" {{ old('objective') == 'OUTCOME_SALES' ? 'selected' : '' }}>Sales</option>
                    <option value="OUTCOME_ENGAGEMENT" {{ old('objective') == 'OUTCOME_ENGAGEMENT' ? 'selected' : '' }}>Engagement</option>
                    <option value="OUTCOME_AWARENESS" {{ old('objective') == 'OUTCOME_AWARENESS' ? 'selected' : '' }}>Awareness</option>
                    <option value="OUTCOME_TRAFFIC" {{ old('objective') == 'OUTCOME_TRAFFIC' ? 'selected' : '' }}>Traffic</option>
                    <option value="OUTCOME_APP_PROMOTION" {{ old('objective') == 'OUTCOME_APP_PROMOTION' ? 'selected' : '' }}>App Promotion</option>
                </select>
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                    Select the main goal for your campaign
                </p>
            </div>

            <!-- Special Ad Categories (Multi-select Tags) -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    Special Ad Categories
                </label>
                <div style="border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem; background-color: white;">
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        <!-- Credit Tag -->
                        <label style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background-color: #f3f4f6; border: 2px solid #e5e7eb; border-radius: 0.375rem; cursor: pointer; transition: all 0.2s;" class="category-tag">
                            <input 
                                type="checkbox" 
                                name="special_ad_categories[]" 
                                value="CREDIT"
                                {{ is_array(old('special_ad_categories')) && in_array('CREDIT', old('special_ad_categories')) ? 'checked' : '' }}
                                style="margin-right: 0.5rem;"
                            >
                            <span style="font-size: 0.875rem; font-weight: 500; color: #374151;">Credit</span>
                        </label>

                        <!-- Housing Tag -->
                        <label style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background-color: #f3f4f6; border: 2px solid #e5e7eb; border-radius: 0.375rem; cursor: pointer; transition: all 0.2s;" class="category-tag">
                            <input 
                                type="checkbox" 
                                name="special_ad_categories[]" 
                                value="HOUSING"
                                {{ is_array(old('special_ad_categories')) && in_array('HOUSING', old('special_ad_categories')) ? 'checked' : '' }}
                                style="margin-right: 0.5rem;"
                            >
                            <span style="font-size: 0.875rem; font-weight: 500; color: #374151;">Housing</span>
                        </label>

                        <!-- Employment Tag -->
                        <label style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background-color: #f3f4f6; border: 2px solid #e5e7eb; border-radius: 0.375rem; cursor: pointer; transition: all 0.2s;" class="category-tag">
                            <input 
                                type="checkbox" 
                                name="special_ad_categories[]" 
                                value="EMPLOYMENT"
                                {{ is_array(old('special_ad_categories')) && in_array('EMPLOYMENT', old('special_ad_categories')) ? 'checked' : '' }}
                                style="margin-right: 0.5rem;"
                            >
                            <span style="font-size: 0.875rem; font-weight: 500; color: #374151;">Employment</span>
                        </label>
                    </div>
                </div>
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                    Select categories if your ad relates to credit, housing, or employment (optional)
                </p>
            </div>

            <!-- Status -->
            <div style="margin-bottom: 1.5rem;">
                <label for="status" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    Status <span style="color: #ef4444;">*</span>
                </label>
                <select
                    id="status"
                    name="status"
                    required
                    style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: white; cursor: pointer;"
                >
                    <option value="PAUSED" {{ old('status') == 'PAUSED' ? 'selected' : '' }}>Paused</option>
                    <option value="ACTIVE" {{ old('status') == 'ACTIVE' ? 'selected' : '' }}>Active</option>
                </select>
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                    Set whether the campaign should be active or paused
                </p>
            </div>

            <!-- Buying Type -->
            <div style="margin-bottom: 1.5rem;">
                <label for="buying_type" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    Buying Type <span style="color: #ef4444;">*</span>
                </label>
                <select
                    id="buying_type"
                    name="buying_type"
                    required
                    style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: white; cursor: pointer;"
                >
                    <option value="">Select buying type</option>
                    <option value="AUCTION" {{ old('buying_type') == 'AUCTION' ? 'selected' : '' }}>Auction</option>
                    <option value="RESERVED" {{ old('buying_type') == 'RESERVED' ? 'selected' : '' }}>Reserved</option>
                </select>
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                    Choose how you want to buy ads
                </p>
            </div>

            <!-- AdSet Budget Sharing -->
            <div style="margin-bottom: 1.5rem;">
                <label for="is_adset_budget_sharing_enabled" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    AdSet Budget Sharing <span style="color: #ef4444;">*</span>
                </label>
                <select
                    id="is_adset_budget_sharing_enabled"
                    name="is_adset_budget_sharing_enabled"
                    required
                    style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: white; cursor: pointer;"
                >
                    <option value="0" {{ old('is_adset_budget_sharing_enabled') === '0' ? 'selected' : '' }}>false</option>
                    <option value="1" {{ old('is_adset_budget_sharing_enabled') === '1' ? 'selected' : '' }}>true</option>
                </select>
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                    Enable or disable budget sharing across ad sets
                </p>
            </div>

            <!-- Daily Budget (Conditionally shown) -->
            <div id="dailyBudgetContainer" style="margin-bottom: 2rem; display: none;">
                <label for="daily_budget" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    Daily Budget (₹) <span style="color: #ef4444;">*</span>
                </label>
                <input
                    type="number"
                    id="daily_budget"
                    name="daily_budget"
                    value="{{ old('daily_budget') }}"
                    placeholder="Enter daily budget amount"
                    step="0.01"
                    min="0"
                    style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                >
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                    Set the daily budget for this campaign
                </p>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 1rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span id="btnText">Create Campaign</span>
                    <span id="btnLoading" style="display: none; align-items: center; gap: 0.5rem;">
                        <span class="spinner"></span>
                        Creating...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<style>
/* Tag hover and checked states */
.category-tag:hover {
    background-color: #e5e7eb;
}

.category-tag input:checked ~ span {
    color: #1d4ed8;
    font-weight: 600;
}

.category-tag:has(input:checked) {
    background-color: #dbeafe;
    border-color: #3b82f6;
}
</style>

<script>
// Toggle Daily Budget field based on AdSet Budget Sharing
function toggleDailyBudget() {
    const budgetSharingSelect = document.getElementById('is_adset_budget_sharing_enabled');
    const dailyBudgetContainer = document.getElementById('dailyBudgetContainer');
    const dailyBudgetInput = document.getElementById('daily_budget');
    
    if (budgetSharingSelect.value === '1') {
        // Show daily budget when true (1)
        dailyBudgetContainer.style.display = 'block';
        dailyBudgetInput.required = true;
    } else {
        // Hide daily budget when false (0) or not selected
        dailyBudgetContainer.style.display = 'none';
        dailyBudgetInput.required = false;
        dailyBudgetInput.value = ''; // Clear value when hidden
    }
}

// Listen for changes on AdSet Budget Sharing dropdown
document.getElementById('is_adset_budget_sharing_enabled').addEventListener('change', toggleDailyBudget);

// Check on page load (for old values after validation errors)
document.addEventListener('DOMContentLoaded', function() {
    toggleDailyBudget();
});

// Form submit loading state
document.getElementById('campaignForm').addEventListener('submit', function() {
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnLoading = document.getElementById('btnLoading');
    
    submitBtn.disabled = true;
    btnText.style.display = 'none';
    btnLoading.style.display = 'inline-flex';
});
</script>
@endpush
@endsection
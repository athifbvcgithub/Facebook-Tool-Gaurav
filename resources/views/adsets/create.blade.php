@extends('layouts.ads-layout')

@section('ads-content')
<!-- Page Title with Back Button -->
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
    <h1 class="page-title" style="margin-bottom: 0;">Create AdSet</h1>
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
        <form method="POST" action="{{ route('adsets.store') }}" id="adsetForm">
            @csrf

            <!-- AdSet Name -->
            <div style="margin-bottom: 1.5rem;">
                <label for="name" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    AdSet Name <span style="color: #ef4444;">*</span>
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter adset name"
                    required
                    style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                >
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                    Choose a descriptive name for your adset
                </p>
            </div>

            <!-- Campaign -->
            <div style="margin-bottom: 1.5rem;">
                <label for="campaign_id" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    Campaign <span style="color: #ef4444;">*</span>
                </label>
                <select id="campaign_id" name="campaign_id" required
                    style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: white; cursor: pointer;"
                >
                    <option value="">Select a campaign</option>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->campaign_id }}" {{ old('campaign_id') == $campaign->campaign_id ? 'selected' : '' }}>
                            {{ $campaign->name }}@if($campaign->campaign_id) ({{ $campaign->campaign_id }})@endif
                        </option>
                    @endforeach
                </select>
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                    Select the campaign this adset belongs to
                </p>
            </div>

            <!-- Daily Budget -->
            <div style="margin-bottom: 1.5rem;">
                <label for="daily_budget" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    Daily Budget <span style="color: #ef4444;">*</span>
                </label>
                <input
                    type="number"
                    id="daily_budget"
                    name="daily_budget"
                    value="{{ old('daily_budget') }}"
                    placeholder="Amount in cents (₹90 = 9000 cents)"
                    required
                    min="0"
                    step="1"
                    style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                >
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                    Enter daily budget in cents (e.g., 9000 cents = ₹90)
                </p>
            </div>

            <!-- Billing Event -->
            <div style="margin-bottom: 1.5rem;">
                <label for="billing_event" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    Billing Event <span style="color: #ef4444;">*</span>
                </label>
                <select
                    id="billing_event"
                    name="billing_event"
                    required
                    style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: white; cursor: pointer;"
                >
                    <option value="">Select billing event</option>
                    <option value="IMPRESSIONS" {{ old('billing_event') == 'IMPRESSIONS' ? 'selected' : '' }}>Impressions</option>
                    <option value="LINK_CLICKS" {{ old('billing_event') == 'LINK_CLICKS' ? 'selected' : '' }}>Link Clicks</option>
                    <option value="POST_ENGAGEMENT" {{ old('billing_event') == 'POST_ENGAGEMENT' ? 'selected' : '' }}>Post Engagement</option>
                </select>
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                    Choose what you'll be billed for
                </p>
            </div>

            <!-- Optimization Goal -->
            <div style="margin-bottom: 1.5rem;">
                <label for="optimization_goal" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    Optimization Goal <span style="color: #ef4444;">*</span>
                </label>
                <select
                    id="optimization_goal"
                    name="optimization_goal"
                    required
                    style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: white; cursor: pointer;"
                >
                    <option value="">Select optimization goal</option>
                    <option value="LINK_CLICKS" {{ old('optimization_goal') == 'LINK_CLICKS' ? 'selected' : '' }}>Link Clicks</option>
                    <option value="IMPRESSIONS" {{ old('optimization_goal') == 'IMPRESSIONS' ? 'selected' : '' }}>Impressions</option>
                    <option value="CONVERSIONS" {{ old('optimization_goal') == 'CONVERSIONS' ? 'selected' : '' }}>Conversions</option>
                    <option value="REACH" {{ old('optimization_goal') == 'REACH' ? 'selected' : '' }}>Reach</option>
                </select>
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                    What should the system optimize for?
                </p>
            </div>

            <!-- Bid Amount -->
            <div style="margin-bottom: 1.5rem;">
                <label for="bid_amount" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    Bid Amount <span style="color: #ef4444;">*</span>
                </label>
                <input
                    type="number"
                    id="bid_amount"
                    name="bid_amount"
                    value="{{ old('bid_amount') }}"
                    placeholder="Enter bid amount"
                    required
                    min="0"
                    step="0.01"
                    style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                >
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                    Enter your maximum bid amount
                </p>
            </div>

            <!-- Targeting Section Header -->
            <div style="margin-bottom: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                <h2 style="font-size: 1.125rem; font-weight: 600; color: #111827;">Targeting</h2>
            </div>

            <!-- Location -->
            <div style="margin-bottom: 1.5rem;">
                <label for="targeting" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    Location <span style="color: #ef4444;">*</span>
                </label>
                <select
                    id="targeting"
                    name="targeting"
                    required
                    style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: white; cursor: pointer;"
                >
                    <option value="">Select location</option>
                    <option value="Delhi" {{ old('targeting') == 'Delhi' ? 'selected' : '' }}>Delhi</option>
                    <option value="Mumbai" {{ old('targeting') == 'Mumbai' ? 'selected' : '' }}>Mumbai</option>
                </select>
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                    Choose target location for your ads
                </p>
            </div>

            <!-- Age Range -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label for="age_min" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Minimum Age <span style="color: #ef4444;">*</span>
                    </label>
                    <input
                        type="number"
                        id="age_min"
                        name="age_min"
                        value="{{ old('age_min', 18) }}"
                        placeholder="18"
                        required
                        min="13"
                        max="65"
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                    >
                </div>
                <div>
                    <label for="age_max" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Maximum Age <span style="color: #ef4444;">*</span>
                    </label>
                    <input
                        type="number"
                        id="age_max"
                        name="age_max"
                        value="{{ old('age_max', 65) }}"
                        placeholder="65"
                        required
                        min="13"
                        max="65"
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                    >
                </div>
            </div>

            <!-- Gender -->
            <div style="margin-bottom: 1.5rem;">
                <label for="genders" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    Gender <span style="color: #ef4444;">*</span>
                </label>
                <select
                    id="genders"
                    name="genders"
                    required
                    style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: white; cursor: pointer;"
                >
                    <option value="">Select gender</option>
                    <option value="1" {{ old('genders') == '1' ? 'selected' : '' }}>Male</option>
                    <option value="2" {{ old('genders') == '2' ? 'selected' : '' }}>Female</option>
                    <option value="both" {{ old('genders') == 'both' ? 'selected' : '' }}>Both</option>
                </select>
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                    Select target gender for your ads
                </p>
            </div>

            <!-- Schedule Section Header -->
            <div style="margin-bottom: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                <h2 style="font-size: 1.125rem; font-weight: 600; color: #111827;">Schedule</h2>
            </div>

            <!-- Start and End Time -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label for="start_time" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Start Time <span style="color: #ef4444;">*</span>
                    </label>
                    <input
                        type="datetime-local"
                        id="start_time"
                        name="start_time"
                        value="{{ old('start_time') }}"
                        required
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                    >
                </div>
                <div>
                    <label for="end_time" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        End Time <span style="color: #ef4444;">*</span>
                    </label>
                    <input
                        type="datetime-local"
                        id="end_time"
                        name="end_time"
                        value="{{ old('end_time') }}"
                        required
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                    >
                </div>
            </div>

            <!-- Status -->
            <div style="margin-bottom: 2rem;">
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
                    Set whether the adset should be active or paused
                </p>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 1rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span id="btnText">Create AdSet</span>
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
<script>
document.getElementById('adsetForm').addEventListener('submit', function() {
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
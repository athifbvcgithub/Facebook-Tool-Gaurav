@extends('layouts.ads-layout')

@section('ads-content')

<style>
.step-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 3rem;
}

.step {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.125rem;
    background-color: #e5e7eb;
    color: #9ca3af;
    position: relative;
}

.step.active {
    background-color: #1f2937;
    color: white;
}

.step.completed {
    background-color: #10b981;
    color: white;
}

.step-line {
    width: 100px;
    height: 2px;
    background-color: #e5e7eb;
}

.step-line.completed {
    background-color: #10b981;
}

.form-step {
    display: none;
}

.form-step.active {
    display: block;
}
</style>

<!-- Header -->
<div style="margin-bottom: 2rem;">
    <h1 class="page-title">Ad Launcher / Create ads</h1>
</div>

<!-- Success Messages -->
@if(session('success'))
<div class="alert alert-success" style="margin-bottom: 2rem;">
    <div class="alert-icon">
        <svg fill="currentColor" viewBox="0 0 20 20" style="width: 1.25rem; height: 1.25rem;">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
    </div>
    <div class="alert-content">
        <p class="alert-message">{{ session('success') }}</p>
    </div>
</div>
@endif

<!-- Error Messages -->
@if(session('error'))
<div class="alert alert-error" style="margin-bottom: 2rem;">
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

<!-- Validation Errors -->
@if($errors->any())
<div class="alert alert-error" style="margin-bottom: 2rem;">
    <div class="alert-icon">
        <svg fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
    </div>
    <div class="alert-content">
        <p class="alert-message" style="font-weight: 600;">Please fix the following errors:</p>
        <ul style="margin-top: 0.5rem; padding-left: 1.25rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<!-- Multi-Step Form Card -->
<div class="card">
    <div style="padding: 2rem;">
        
        <!-- Step Indicator -->
        <div class="step-container">
            <div class="step active" id="step-indicator-1">1</div>
            <div class="step-line" id="line-1"></div>
            <div class="step" id="step-indicator-2">2</div>
            <div class="step-line" id="line-2"></div>
            <div class="step" id="step-indicator-3">3</div>
            <div class="step-line" id="line-3"></div>
            <div class="step" id="step-indicator-4">4</div>
        </div>

        <form method="POST" action="{{ route('ad-launcher.store') }}" id="adLauncherForm" enctype="multipart/form-data">
            @csrf

            <!-- Step 1: Ad Account -->
            <div class="form-step active" id="step-1">
                <h2 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 2rem; color: #111827;">Ad Account</h2>

                <!-- Preset -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="preset" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Preset
                    </label>
                    <select
                        id="preset"
                        name="preset"
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: white; cursor: pointer;"
                    >
                        <option value="">Please select</option>
                        @foreach($presets as $key => $value)
                            <option value="{{ $key }}" {{ old('preset') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Provider -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="provider" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Provider <span style="color: #ef4444;">*</span>
                    </label>
                    <select
                        id="provider"
                        name="provider"
                        required
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: white; cursor: pointer;"
                    >
                        <option value="">Please select</option>
                        @foreach($providers as $key => $value)
                            <option value="{{ $key }}" {{ old('provider') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Ad Account -->
                <div style="margin-bottom: 2rem;">
                    <label for="ad_account" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Ad Account
                    </label>
                    <!-- <input type="text" id="ad_account" name="ad_account" value="{{ old('ad_account', 'act_1083199717260755') }}"
                        placeholder="" style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db;
                            border-radius: 0.5rem; outline: none; background-color: white;"> -->
                    <select
                        id="ad_account"
                        name="ad_account"
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: white; cursor: pointer;"
                    >
                        <option value="">Please select a provider first</option>
                        @if(count($adAccounts) > 0)
                            @foreach($adAccounts as $account)
                                @if(is_array($account))
                                    <option value="{{ $account['id'] }}" {{ old('ad_account') == $account['id'] ? 'selected' : '' }}>
                                        {{ $account['name'] }} ({{ $account['id'] }})
                                    </option>
                                @else
                                    {{-- Fallback for key-value pairs --}}
                                    <option value="{{ $account }}" {{ old('ad_account') == $account ? 'selected' : '' }}>
                                        {{ $account }}
                                    </option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Next Button -->
                <div style="display: flex; justify-content: flex-end;">
                    <button type="button" class="btn btn-primary" onclick="nextStep(2)">Next</button>
                </div>
            </div>

            <!-- Step 2: Facebook Campaign Settings -->
            <div class="form-step" id="step-2">
                <h2 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 2rem; color: #111827;">
                    Facebook Campaign Settings
                </h2>

                <!-- Campaign Name -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="campaign_name"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Campaign Name <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" id="campaign_name" name="campaign_name" value="{{ old('campaign_name') }}"
                        placeholder="{language} - {country} - {query} - {id}" required
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db;
                            border-radius: 0.5rem; outline: none; background-color: white;">
                </div>

                <!-- Campaign Objective -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="campaign_objective"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Campaign Objective <span style="color: #ef4444;">*</span>
                    </label>
                    <select id="campaign_objective" name="campaign_objective" required
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db;
                            border-radius: 0.5rem; outline: none; background-color: white; cursor: pointer;">
                        <option value="">Please select</option>
                        @foreach($campaignObjectives as $key => $value)
                            <option value="{{ $key }}" {{ old('campaign_objective') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Special Ad Categories -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="special_ad_categories"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Special Ad Categories
                    </label>
                    <select id="special_ad_categories" name="special_ad_categories"
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db;
                            border-radius: 0.5rem; outline: none; background-color: white; cursor: pointer;">
                        <option value="">Please select</option>
                        @foreach($specialAdCategories as $key => $value)
                            <option value="{{ $key }}" {{ old('special_ad_categories') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Campaign Daily Budget -->
                {{--<div style="margin-bottom: 2rem;">
                    <label for="campaign_budget"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Campaign Daily Budget <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="display: flex; align-items: center;">
                        <input type="number" id="campaign_budget" name="campaign_budget" min="1" step="1" value="{{ old('campaign_budget',1) }}"
                            required
                            style="flex: 1; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db;
                                border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem;
                                outline: none; background-color: white;">
                        <span style="padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db;
                                    border-left: none; border-top-right-radius: 0.5rem;
                                    border-bottom-right-radius: 0.5rem; background-color: #f9fafb;">INR</span>
                    </div>
                </div> --}}

                <!-- Buttons -->
                <div style="display: flex; justify-content: space-between;">
                    <button type="button" class="btn btn-secondary" onclick="prevStep(1)">Back</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep(3)">Next</button>
                </div>
            </div>


            <!-- Step 3: Facebook Adset Settings -->
            <div class="form-step" id="step-3">
                <h2 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 2rem; color: #111827;">
                    Facebook Adset Settings
                </h2>

                <!-- Adset Name -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="adset_name"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Adset Name <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" id="adset_name" name="adset_name" value="{{ old('adset_name') }}"
                        placeholder="{language} - {country} - {query} - {targeting} - {id}" required
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem;
                            border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;">
                </div>

                <!-- Performance Goal -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="performance_goal"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Performance Goal
                    </label>
                    <select id="performance_goal" name="performance_goal"
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem;
                            border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: white;">
                        <option value="LINK_CLICKS"{{ old('performance_goal') == 'LINK_CLICKS' ? 'selected' : '' }}>LINK CLICKS</option>
                        <option value="REACH"{{ old('performance_goal') == 'REACH' ? 'selected' : '' }}>REACH</option>
                        <option value="IMPRESSIONS"{{ old('performance_goal') == 'IMPRESSIONS' ? 'selected' : '' }}>IMPRESSIONS</option>
                        <option value="CONVERSIONS"{{ old('performance_goal') == 'CONVERSIONS' ? 'selected' : '' }}>CONVERSIONS</option>
                    </select>
                </div>

                <!-- Pixel -->
                <div style="margin-bottom: 2rem;">
                    <label for="pixel"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Pixel <span style="color: #ef4444;">*</span>
                    </label>
                    <select id="pixel" name="pixel" required
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem;
                            border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: white;">
                        <option value="">Please select</option>
                        @foreach($pixels as $key => $value)
                            <option value="{{ $key }}"{{ old('pixel') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Budget & Schedule -->
                <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1.5rem; color: #111827;">Budget & Schedule</h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    <!-- Adset Budget -->
                    <div>
                        <label for="adset_budget"
                            style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                            Adset Budget <span style="color: #ef4444;">*</span>
                        </label>
                        <div style="display: flex; align-items: center;">
                            <input type="number" id="adset_budget" name="adset_budget" value="{{ old('adset_budget',90) }}" min="1" required
                                style="flex: 1; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db;
                                    border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem;">
                            <span style="padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-left: none;
                                        border-top-right-radius: 0.5rem; border-bottom-right-radius: 0.5rem;
                                        background-color: #f9fafb;">INR</span>
                        </div>
                    </div>

                    <!-- Cost per result goal -->
                    <div>
                        <label for="cost_goal"
                            style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                            Cost per result goal
                        </label>
                        <div style="display: flex; align-items: center;">
                            <input type="text" id="cost_goal" name="cost_goal" value="{{ old('cost_goal',1) }}" placeholder="optional"
                                style="flex: 1; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db;
                                    border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem;">
                            <span style="padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-left: none;
                                        border-top-right-radius: 0.5rem; border-bottom-right-radius: 0.5rem;
                                        background-color: #f9fafb;">INR</span>
                        </div>
                    </div>

                    <!-- Bid Strategy -->
                    <div>
                        <label for="bid_strategy"
                            style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                            Bid Strategy
                        </label>
                        <select id="bid_strategy" name="bid_strategy"
                            style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
                            <!-- <option value="">Using highest volume strategy</option> -->
                            <option value="">Auto (Recommended)</option>
                            <option value="LOWEST_COST_WITHOUT_CAP">Lowest Cost</option>
                            <option value="cost_cap" {{ old('bid_strategy') == 'cost_cap' ? 'selected' : '' }}>Cost Cap</option>
                            <option value="bid_cap" {{ old('bid_strategy') == 'bid_cap' ? 'selected' : '' }}>Bid Cap</option>
                        </select>
                    </div>

                    <!-- Billing Event -->
                    <div>
                        <label for="billing_event"
                            style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                            Billing Event <span style="color: #ef4444;">*</span>
                        </label>
                        <select id="billing_event" name="billing_event" required
                            style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
                            <option value="IMPRESSIONS" {{ old('billing_event') == 'IMPRESSIONS' ? 'selected' : '' }}>Impressions</option>
                            <option value="LINK_CLICKS" {{ old('billing_event') == 'LINK_CLICKS' ? 'selected' : '' }}>LINK CLICKS</option>
                            <option value="POST_ENGAGEMENT" {{ old('billing_event') == 'POST_ENGAGEMENT' ? 'selected' : '' }}>POST ENGAGEMENT</option>
                        </select>
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date"
                            style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                            Start Date
                        </label>
                        <input type="datetime-local" id="start_date" name="start_date" value="{{ old('start_date') }}"
                            style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
                    </div>

                    <!-- Timezone -->
                    <div>
                        <label for="timezone"
                            style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                            Timezone
                        </label>
                        <select id="timezone" name="timezone"
                            style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
                            <option value="">Please select</option>
                            <option value="Asia/Kolkata" {{ old('timezone', 'Asia/Kolkata') == 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (IST)</option>
                            <option value="UTC" {{ old('timezone') == 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="America/New_York" {{ old('timezone') == 'Asia/Kolkata' ? 'selected' : '' }}>US/Eastern (EST)</option>
                            <option value="America/Los_Angeles" {{ old('timezone') == 'Asia/Kolkata' ? 'selected' : '' }}>US/Pacific (PST)</option>
                            <option value="Europe/London" {{ old('timezone') == 'Asia/Kolkata' ? 'selected' : '' }}>Europe/London (GMT)</option>
                        </select>
                    </div>
                </div>

                <!-- Targeting -->
                <h3 style="font-size: 1.125rem; font-weight: 600; margin: 2rem 0 1.5rem; color: #111827;">Targeting</h3>

                <!-- Page -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="page"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Page <span style="color: #ef4444;">*</span>
                    </label>
                    <select id="page" name="page" required
                        style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
                        <option value="">Please select</option>
                        @if(count($pages) > 0)
                            @foreach($pages as $key => $value)
                                <option value="{{ $key }}" {{ old('page') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        @else
                            <option value="614026428456784" {{ old('page') == '614026428456784' ? 'selected' : '' }}>614026428456784</option>
                        @endif
                    </select>
                </div>

                <!-- Country Preset -->
                <div style="margin-bottom: 2rem;">
                    <label for="country_preset"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Country Preset
                    </label>
                    <select id="country_preset" name="country_preset"
                        style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
                        <option value="">Please select</option>
                        @foreach($countryPresets as $key => $value)
                            <option value="{{ $key }}" {{ old('country_preset') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Buttons -->
                <div style="display: flex; justify-content: space-between;">
                    <button type="button" class="btn btn-secondary" onclick="prevStep(2)">Back</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep(4)">Next</button>
                </div>
            </div>


            <!-- Step 4: Ad Creative & Content -->
            <div class="form-step" id="step-4">
                <h2 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 2rem; color: #111827;">
                    Ad Creative & Content
                </h2>

                <!-- Ad Name -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="ad_name"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Ad Name <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" id="ad_name" name="ad_name" value="{{ old('ad_name') }}"
                        placeholder="{language} - {country} - {creative_type} - {id}" required
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem;
                            border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;">
                </div>

                <!-- Ad Format -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="ad_format"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Ad Format <span style="color: #ef4444;">*</span>
                    </label>
                    <select id="ad_format" name="ad_format" required
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem;
                            border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: white;">
                        <option value="">Please select</option>
                        <option value="single_image" {{ old('ad_format') == 'single_image' ? 'selected' : '' }}>Single Image</option>
                        <option value="single_video" {{ old('ad_format') == 'single_video' ? 'selected' : '' }}>Single Video</option>
                        <option value="carousel" {{ old('ad_format') == 'carousel' ? 'selected' : '' }}>Carousel</option>
                        <option value="collection" {{ old('ad_format') == 'collection' ? 'selected' : '' }}>Collection</option>
                    </select>
                </div>

                <!-- Creative Section -->
                <h3 style="font-size: 1.125rem; font-weight: 600; margin: 2rem 0 1.5rem; color: #111827;">Creative Assets</h3>

                <!-- Primary Text -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="primary_text"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Primary Text <span style="color: #ef4444;">*</span>
                    </label>
                    <textarea id="primary_text" name="primary_text" rows="3" required
                        placeholder="Write your ad copy here... (125 characters recommended)"
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem;
                            border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; resize: vertical;">{{ old('primary_text') }}</textarea>
                    <span style="font-size: 0.75rem; color: #6b7280;">Character count: <span id="primary_text_count">0</span>/125</span>
                </div>

                <!-- Headline -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="headline"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Headline <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" id="headline" name="headline" value="{{ old('headline') }}" maxlength="40" required
                        placeholder="Attention-grabbing headline (40 characters max)"
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem;
                            border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;">
                    <span style="font-size: 0.75rem; color: #6b7280;">Character count: <span id="headline_count">0</span>/40</span>
                </div>

                <!-- Description -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="description"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Description (Optional)
                    </label>
                    <input type="text" id="description" name="description" value="{{ old('description') }}" maxlength="30"
                        placeholder="Additional description (30 characters max)"
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem;
                            border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;">
                    <span style="font-size: 0.75rem; color: #6b7280;">Character count: <span id="description_count">0</span>/30</span>
                </div>

                <!-- Media Upload -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="media"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Media (Image/Video) <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="border: 2px dashed #d1d5db; border-radius: 0.5rem; padding: 2rem; text-align: center; background-color: #f9fafb;">
                        <svg style="width: 3rem; height: 3rem; margin: 0 auto 1rem; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <input type="file" id="media" name="media" accept="image/*,video/*" required
                            style="display: none;" onchange="displayFileName(this)">
                        <button type="button" onclick="document.getElementById('media').click()"
                            style="padding: 0.5rem 1rem; background-color: white; border: 1px solid #d1d5db;
                                border-radius: 0.375rem; cursor: pointer; font-size: 0.875rem; font-weight: 500;">
                            Upload Image/Video
                        </button>
                        <p style="margin-top: 0.5rem; font-size: 0.75rem; color: #6b7280;">
                            Recommended: 1080 x 1080px | JPG, PNG, MP4 | Max 30MB (image), 4GB (video)
                        </p>
                        <p id="file_name" style="margin-top: 0.5rem; font-size: 0.875rem; color: #111827; font-weight: 500;"></p>
                    </div>
                </div>

                <!-- Call to Action Section -->
                <h3 style="font-size: 1.125rem; font-weight: 600; margin: 2rem 0 1.5rem; color: #111827;">Call to Action</h3>

                <!-- CTA Button -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="cta_button"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        CTA Button <span style="color: #ef4444;">*</span>
                    </label>
                    <select id="cta_button" name="cta_button" required
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem;
                            border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: white;">
                        <option value="">Please select</option>
                        <option value="learn_more" {{ old('cta_button') == 'learn_more' ? 'selected' : '' }}>Learn More</option>
                        <option value="shop_now" {{ old('cta_button') == 'shop_now' ? 'selected' : '' }}>Shop Now</option>
                        <option value="sign_up" {{ old('cta_button') == 'sign_up' ? 'selected' : '' }}>Sign Up</option>
                        <option value="download" {{ old('cta_button') == 'download' ? 'selected' : '' }}>Download</option>
                        <option value="contact_us" {{ old('cta_button') == 'contact_us' ? 'selected' : '' }}>Contact Us</option>
                        <option value="apply_now" {{ old('cta_button') == 'apply_now' ? 'selected' : '' }}>Apply Now</option>
                        <option value="book_now" {{ old('cta_button') == 'book_now' ? 'selected' : '' }}>Book Now</option>
                        <option value="get_quote" {{ old('cta_button') == 'get_quote' ? 'selected' : '' }}>Get Quote</option>
                    </select>
                </div>

                <!-- Website URL -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="website_url"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Website URL <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="url" id="website_url" name="website_url" value="{{ old('website_url') }}" required
                        placeholder="https://example.com"
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem;
                            border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;">
                </div>

                <!-- Display Link -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="display_link"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Display Link (Optional)
                    </label>
                    <input type="text" id="display_link" name="display_link" value="{{ old('display_link') }}"
                        placeholder="example.com/shop"
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem;
                            border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;">
                </div>

                <!-- Tracking Section -->
                <h3 style="font-size: 1.125rem; font-weight: 600; margin: 2rem 0 1.5rem; color: #111827;">Tracking & Optimization</h3>

                <!-- URL Parameters -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="url_parameters"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        URL Parameters (Optional)
                    </label>
                    <input type="text" id="url_parameters" name="url_parameters" value="{{ old('url_parameters') }}"
                        placeholder="utm_source=facebook&utm_medium=cpc&utm_campaign=..."
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem;
                            border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;">
                </div>

                <!-- Pixel Event -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="pixel_event"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Facebook Pixel Event <span style="color: #ef4444;">*</span>
                    </label>
                    <select id="pixel_event" name="pixel_event" required
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem;
                            border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: white;">
                        <option value="">Please select</option>
                        <option value="ViewContent" {{ old('pixel_event') == 'ViewContent' ? 'selected' : '' }}>ViewContent</option>
                        <option value="AddToCart" {{ old('pixel_event') == 'AddToCart' ? 'selected' : '' }}>AddToCart</option>
                        <option value="Purchase" {{ old('pixel_event') == 'Purchase' ? 'selected' : '' }}>Purchase</option>
                        <option value="Lead" {{ old('pixel_event') == 'Lead' ? 'selected' : '' }}>Lead</option>
                        <option value="CompleteRegistration" {{ old('pixel_event') == 'CompleteRegistration' ? 'selected' : '' }}>Complete Registration</option>
                    </select>
                </div>

                <!-- Status -->
                <div style="margin-bottom: 2rem;">
                    <label for="status"
                        style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Status <span style="color: #ef4444;">*</span>
                    </label>
                    <select id="status" name="status" required
                        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem;
                            border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: white;">
                        <option value="paused"{{ old('status') == 'paused' ? 'selected' : '' }}>Paused</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        
                    </select>
                </div>

                <!-- Buttons -->
                <div style="display: flex; justify-content: space-between;">
                    <button type="button" class="btn btn-secondary" onclick="prevStep(3)">Back</button>
                    <button type="submit" class="btn btn-primary">Create Campaign</button>
                </div>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
let currentStep = 1;

// Character counters
document.getElementById('primary_text').addEventListener('input', function() {
    document.getElementById('primary_text_count').textContent = this.value.length;
});

document.getElementById('headline').addEventListener('input', function() {
    document.getElementById('headline_count').textContent = this.value.length;
});

document.getElementById('description').addEventListener('input', function() {
    document.getElementById('description_count').textContent = this.value.length;
});

// File name display
function displayFileName(input) {
    const fileName = input.files[0]?.name || '';
    const fileNameElement = document.getElementById('file_name');
    if (fileName) {
        fileNameElement.textContent = '✓ Selected: ' + fileName;
        fileNameElement.style.color = '#10b981';
    } else {
        fileNameElement.textContent = '';
    }
}

// Load ad accounts when provider changes
/* document.getElementById('provider').addEventListener('change', function() {
    const provider = this.value;
    const adAccountSelect = document.getElementById('ad_account');
    
    if (provider) {
        // Show loading state
        adAccountSelect.innerHTML = '<option value="">Loading...</option>';
        //adAccountSelect.disabled = true;
        
        // Fetch ad accounts via AJAX
        fetch(`/api/ad-launcher/ad-accounts?provider=${provider}`)
            .then(response => response.json())
            .then(data => {
                //adAccountSelect.innerHTML = '<option value="">Please select</option>';
                
                if (data.length > 0) {
                    data.forEach(account => {
                        //adAccountSelect.innerHTML += `<option value="${account.id}">${account.name}</option>`;
                    });
                    //adAccountSelect.disabled = false;
                    //adAccountSelect.required = true;
                } else {
                    adAccountSelect.innerHTML = '<option value="">No ad accounts found</option>';
                    adAccountSelect.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error fetching ad accounts:', error);
                //adAccountSelect.innerHTML = '<option value="">Error loading accounts</option>';
                //adAccountSelect.disabled = true;
            });
    } else {
        adAccountSelect.innerHTML = '<option value="">Please select a provider first</option>';
        //adAccountSelect.disabled = true;
        //adAccountSelect.required = false;
    }
}); */

// Load pixels when ad account changes (optional feature)
document.getElementById('ad_account').addEventListener('change', function() {
    const adAccountId = this.value;
    const pixelSelect = document.getElementById('pixel');
    
    if (adAccountId) {
        // Fetch pixels via AJAX
        fetch(`/api/ad-launcher/pixels?ad_account_id=${adAccountId}`)
            .then(response => response.json())
            .then(data => {
                pixelSelect.innerHTML = '<option value="">Please select</option>';
                
                if (data.length > 0) {
                    data.forEach(pixel => {
                        pixelSelect.innerHTML += `<option value="${pixel.id}">${pixel.name}</option>`;
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching pixels:', error);
            });
    }
});

function nextStep(step) {
    const currentStepElement = document.getElementById(`step-${currentStep}`);
    const inputs = currentStepElement.querySelectorAll('input[required], select[required], textarea[required]');
    
    let isValid = true;
    let firstInvalidField = null;
    
    inputs.forEach(input => {
        if (!input.value) {
            isValid = false;
            input.style.borderColor = '#ef4444';
            if (!firstInvalidField) {
                firstInvalidField = input;
            }
        } else {
            input.style.borderColor = '#d1d5db';
        }
    });
    
    if (!isValid) {
        alert('Please fill all required fields');
        if (firstInvalidField) {
            firstInvalidField.focus();
        }
        return;
    }
    
    // Hide current step
    document.getElementById(`step-${currentStep}`).classList.remove('active');
    document.getElementById(`step-indicator-${currentStep}`).classList.add('completed');
    document.getElementById(`step-indicator-${currentStep}`).classList.remove('active');
    
    if (currentStep < step) {
        document.getElementById(`line-${currentStep}`).classList.add('completed');
    }
    
    // Show next step
    document.getElementById(`step-${step}`).classList.add('active');
    document.getElementById(`step-indicator-${step}`).classList.add('active');
    
    currentStep = step;
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function prevStep(step) {
    // Hide current step
    document.getElementById(`step-${currentStep}`).classList.remove('active');
    document.getElementById(`step-indicator-${currentStep}`).classList.remove('active');
    document.getElementById(`step-indicator-${currentStep}`).classList.remove('completed');
    
    if (currentStep > step) {
        document.getElementById(`line-${step}`).classList.remove('completed');
    }
    
    // Show previous step
    document.getElementById(`step-${step}`).classList.add('active');
    document.getElementById(`step-indicator-${step}`).classList.add('active');
    document.getElementById(`step-indicator-${step}`).classList.remove('completed');
    
    currentStep = step;
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Form submission
document.getElementById('adLauncherForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Creating Campaign...';
});
</script>
@endpush

@endsection
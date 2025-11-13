@extends('layouts.settings')

@section('title', 'Add New Connection')

@php
$facebook_oauth_url = sprintf(
    'https://www.facebook.com/%s/dialog/oauth?access_type=offline&client_id=%s&redirect_uri=%s&response_type=code&scope=%s',
    config('services.facebook.version'),
    config('services.facebook.client_id'),
    urlencode(config('services.facebook.redirect')),
    config('services.facebook.scopes')
);
@endphp


@section('settings-content')
<div class="settings-content">
    <h1 class="settings-title">Connections / Add new</h1>

    <div class="connections-grid">
        <!-- Facebook Card -->
        <div class="connection-card" data-bs-toggle="modal" data-bs-target="#facebookModal">
            <img src="{{ asset('images/facebook_logo.png') }}" alt="Facebook" class="connection-logo">
            <div class="connection-name">Facebook</div>
            <div class="connection-details">Connect your Facebook Ads account</div>
            <button class="connect-btn">+ Connect</button>
        </div>
    </div>
</div>

<!-- <iframe src="{{ $facebook_oauth_url }}" style="width:100%; height:100%; border:0;"></iframe>
 -->
@endsection

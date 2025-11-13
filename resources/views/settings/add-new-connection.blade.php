@extends('layouts.settings')

@section('title', 'Add New Connection')

@section('settings-content')
<div class="settings-content">
    <h1 class="settings-title">Connections / Add new</h1>

    <a href="{{ config('services.facebook.oauth_url')() }}" style="text-decoration: none;">
        <div class="connections-grid">
            <!-- Facebook Card -->
            <div class="connection-card" data-bs-toggle="modal" data-bs-target="#facebookModal">
                <img src="{{ asset('images/facebook_logo.png') }}" alt="Facebook" class="connection-logo">
                <div class="connection-name">Facebook</div>
                <div class="connection-details">Connect your Facebook Ads account</div>
                <button class="connect-btn">+ Connect</button>
            </div>
        </div>
    </a>
</div>

@endsection

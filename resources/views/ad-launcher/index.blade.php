@extends('layouts.ads-layout')

@section('ads-content')

<!-- Header -->
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
    <h1 class="page-title" style="margin-bottom: 0;">Ad Launcher</h1>
    <a href="{{ route('ad-launcher.create') }}" class="btn btn-primary">Add</a>
</div>

<!-- Success Message -->
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

<!-- Search Box -->
<div style="margin-bottom: 2rem;">
    <input 
        type="text" 
        id="searchInput"
        placeholder="Search" 
        style="width: 100%; padding: 0.75rem 1rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
    >
</div>

<!-- Table -->
<div class="card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                <tr>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">NAME ↕</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">CAMPAIGN NAMES</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">PROVIDER ↕</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">QUERY ↕</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">AD ACCOUNT ↕</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">LANGUAGE ↕</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">COUNTRY ↕</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">STATUS ↕</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">CREATED ↕</th>
                </tr>
            </thead>
            <tbody>
                @forelse($adLaunchers as $launcher)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 1rem; font-size: 0.875rem; color: #111827;">{{ $launcher->name }}</td>
                    <td style="padding: 1rem; font-size: 0.875rem; color: #111827;">{{ $launcher->campaign_names }}</td>
                    <td style="padding: 1rem; font-size: 0.875rem; color: #111827;">{{ ucfirst($launcher->provider) }}</td>
                    <td style="padding: 1rem; font-size: 0.875rem; color: #111827;">{{ $launcher->query ?? '-' }}</td>
                    <td style="padding: 1rem; font-size: 0.875rem; color: #111827;">{{ $launcher->ad_account }}</td>
                    <td style="padding: 1rem; font-size: 0.875rem; color: #111827;">{{ $launcher->language }}</td>
                    <td style="padding: 1rem; font-size: 0.875rem; color: #111827;">{{ $launcher->country }}</td>
                    <td style="padding: 1rem; font-size: 0.875rem;">
                        <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; {{ $launcher->status === 'active' ? 'background-color: #d1fae5; color: #065f46;' : 'background-color: #fee2e2; color: #991b1b;' }}">
                            {{ ucfirst($launcher->status) }}
                        </span>
                    </td>
                    <td style="padding: 1rem; font-size: 0.875rem; color: #111827;">{{ $launcher->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="padding: 3rem; text-align: center; color: #6b7280; font-size: 0.875rem;">
                        No ad launchers found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
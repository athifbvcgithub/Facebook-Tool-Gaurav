@extends('layouts.app')

@section('content')
<div class="main-content">
    <!-- Page Title -->
    <h1 class="page-title">Dashboard</h1>

    <!-- Stats Cards -->
    <div class="stats-grid mb-8">
        <div class="stat-card">
            <div class="stat-icon bg-blue-100 text-blue-600">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Campaigns</div>
                <div class="stat-value">{{ $totalCampaigns ?? 0 }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-green-100 text-green-600">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Active Ads</div>
                <div class="stat-value">{{ $activeAds ?? 0 }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-purple-100 text-purple-600">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Spend</div>
                <div class="stat-value">₹{{ number_format($totalSpend ?? 0, 2) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-orange-100 text-orange-600">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Impressions</div>
                <div class="stat-value">{{ number_format($totalImpressions ?? 0) }}</div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <h2 class="section-title mb-4">Recent Activity</h2>

    <!-- Recent Ads Table -->
    <div class="card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>
                            <div class="th-sortable">
                                <span>ID</span>
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            </div>
                        </th>
                        <th>TYPE</th>
                        <th>NAME</th>
                        <th>STATUS</th>
                        <th>
                            <div class="th-sortable sorted">
                                <span>CREATED</span>
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentActivity ?? [] as $activity)
                    <tr>
                        <td class="td-nowrap">{{ $activity->id }}</td>
                        <td class="td-nowrap">
                            <span class="badge badge-gray">{{ $activity->type }}</span>
                        </td>
                        <td>{{ $activity->name }}</td>
                        <td class="td-nowrap">
                            <span class="badge {{ $activity->status === 'ACTIVE' ? 'badge-success' : 'badge-gray' }}">
                                {{ $activity->status }}
                            </span>
                        </td>
                        <td class="td-nowrap">{{ $activity->created_at->format('M d, Y') }}</td>
                        <td class="td-nowrap">
                            <a href="#" class="text-blue-600 hover:text-blue-800">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <p class="empty-state-text">No recent activity</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions mt-8">
        <h2 class="section-title mb-4">Quick Actions</h2>
        <div class="action-grid">
            <a href="{{ route('campaigns.create') }}" class="action-card">
                <div class="action-icon bg-blue-100 text-blue-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div class="action-content">
                    <h3 class="action-title">Create Campaign</h3>
                    <p class="action-description">Start a new advertising campaign</p>
                </div>
            </a>

            <a href="{{ route('adsets.create') }}" class="action-card">
                <div class="action-icon bg-green-100 text-green-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div class="action-content">
                    <h3 class="action-title">Create AdSet</h3>
                    <p class="action-description">Add a new ad set to your campaign</p>
                </div>
            </a>

            <a href="{{ route('ads.index') }}" class="action-card">
                <div class="action-icon bg-purple-100 text-purple-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="action-content">
                    <h3 class="action-title">View All Ads</h3>
                    <p class="action-description">Browse and manage your ads</p>
                </div>
            </a>

            <a href="{{ route('stats') }}" class="action-card">
                <div class="action-icon bg-orange-100 text-orange-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="action-content">
                    <h3 class="action-title">View Stats</h3>
                    <p class="action-description">Check your campaign performance</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
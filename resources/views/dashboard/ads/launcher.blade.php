@extends('layouts.ads-layout')

@section('ads-content')
<!-- Page Title with Add Button -->
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
    <h1 class="page-title" style="margin-bottom: 0;">Ad Launcher</h1>
    <a href="{{ route('ad-launcher.create') }}" class="btn btn-primary">Add</a>
</div>

<!-- Search Bar -->
<div class="card search-container">
    <div class="card-padding">
        <div class="search-wrapper">
            <input 
                type="text" 
                placeholder="Search" 
                class="search-input"
            >
            <button class="search-button">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Ad Launchers Table -->
<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>
                        <div class="th-sortable">
                            <span>NAME</span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                        </div>
                    </th>
                    <th>CAMPAIGN NAMES</th>
                    <th>
                        <div class="th-sortable">
                            <span>PROVIDER</span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                        </div>
                    </th>
                    <th>
                        <div class="th-sortable">
                            <span>QUERY</span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                        </div>
                    </th>
                    <th>
                        <div class="th-sortable">
                            <span>AD ACCOUNT</span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                        </div>
                    </th>
                    <th>
                        <div class="th-sortable">
                            <span>LANGUAGE</span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                        </div>
                    </th>
                    <th>
                        <div class="th-sortable">
                            <span>COUNTRY</span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                        </div>
                    </th>
                    <th>
                        <div class="th-sortable">
                            <span>STATUS</span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                        </div>
                    </th>
                    <th>
                        <div class="th-sortable sorted">
                            <span>CREATED</span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($launchers ?? [] as $launcher)
                <tr>
                    <td class="td-nowrap">{{ $launcher->name }}</td>
                    <td class="td-truncate">{{ $launcher->campaign_names }}</td>
                    <td class="td-nowrap">{{ $launcher->provider }}</td>
                    <td class="td-truncate">{{ $launcher->query }}</td>
                    <td class="td-nowrap">{{ $launcher->ad_account }}</td>
                    <td class="td-nowrap">{{ $launcher->language }}</td>
                    <td class="td-nowrap">{{ $launcher->country }}</td>
                    <td class="td-nowrap">
                        <span class="badge {{ $launcher->status === 'ACTIVE' ? 'badge-success' : 'badge-gray' }}">
                            {{ $launcher->status }}
                        </span>
                    </td>
                    <td class="td-nowrap">{{ $launcher->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <p class="empty-state-text">No ad launchers found</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
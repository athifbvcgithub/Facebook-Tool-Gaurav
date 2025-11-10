@extends('layouts.ads-layout')

@section('ads-content')
<!-- Page Title -->
<h1 class="page-title">Ads</h1>

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

<!-- Ads Table -->
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
                    <th>THUMBNAIL</th>
                    <th>
                        <div class="th-sortable">
                            <span>TEXT</span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                        </div>
                    </th>
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
                            <span>AD ACCOUNT</span>
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
                    <th>
                        <div class="th-sortable">
                            <span>LAST SEEN</span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($ads as $ad)
                <tr>
                    <td class="td-nowrap">{{ $ad->id }}</td>
                    <td>
                        @if($ad->thumbnail)
                            <img src="{{ $ad->thumbnail }}" alt="Thumbnail" class="thumbnail">
                        @else
                            <div class="thumbnail-placeholder"></div>
                        @endif
                    </td>
                    <td class="td-truncate">{{ $ad->text }}</td>
                    <td class="td-nowrap">{{ $ad->provider }}</td>
                    <td class="td-nowrap">{{ $ad->ad_account }}</td>
                    <td class="td-nowrap">
                        <span class="badge {{ $ad->status === 'ACTIVE' ? 'badge-success' : 'badge-gray' }}">
                            {{ $ad->status }}
                        </span>
                    </td>
                    <td class="td-nowrap">{{ $ad->created_at->format('M d, Y') }}</td>
                    <td class="td-nowrap">{{ $ad->last_seen ? $ad->last_seen->format('M d, Y') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <p class="empty-state-text">No ads found</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
@extends('layouts.ads-layout')

@section('ads-content')
<!-- Page Title -->
<h1 class="page-title">Ad Launcher Presets</h1>

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

<!-- Presets Table -->
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
                    <th>
                        <div class="th-sortable">
                            <span>DESCRIPTION</span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                        </div>
                    </th>
                    <th>
                        <div class="th-sortable">
                            <span>CAMPAIGNS</span>
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
                @forelse($presets ?? [] as $preset)
                <tr>
                    <td class="td-nowrap">{{ $preset->name }}</td>
                    <td class="td-truncate">{{ $preset->description }}</td>
                    <td class="td-nowrap">{{ $preset->campaigns }}</td>
                    <td class="td-nowrap">{{ $preset->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <p class="empty-state-text">No presets found</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
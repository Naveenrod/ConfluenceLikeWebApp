@extends('layouts.app')

@section('title', 'Spaces - Confluence')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-white">Spaces</h1>
            <p class="text-sm text-confluence-textMuted mt-1">Browse and manage your spaces</p>
        </div>
        <a href="{{ route('spaces.create') }}" class="bg-confluence-blue hover:bg-confluence-blueHover text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
            <i class="fas fa-plus mr-2"></i>Create Space
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center space-x-4 mb-6 border-b border-confluence-border pb-4">
        <button class="text-confluence-blue border-b-2 border-confluence-blue pb-2 text-sm font-medium">All spaces</button>
        <button class="text-confluence-textMuted hover:text-confluence-text pb-2 text-sm">My spaces</button>
        <button class="text-confluence-textMuted hover:text-confluence-text pb-2 text-sm">Starred</button>
        <button class="text-confluence-textMuted hover:text-confluence-text pb-2 text-sm">Archived</button>
    </div>

    <!-- Search -->
    <div class="mb-6">
        <div class="relative max-w-md">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-confluence-textMuted"></i>
            <input type="text" placeholder="Search spaces" class="w-full bg-confluence-card border border-confluence-border rounded-md pl-10 pr-4 py-2 text-sm focus:outline-none focus:border-confluence-blue">
        </div>
    </div>

    <!-- Spaces Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($spaces as $space)
            <div class="bg-confluence-card border border-confluence-border rounded-lg p-4 hover:border-confluence-blue/50 transition group">
                <div class="flex items-start justify-between mb-3">
                    <a href="{{ route('spaces.show', $space) }}" class="flex items-center space-x-3 flex-1">
                        <span class="w-10 h-10 rounded bg-confluence-purple flex items-center justify-center text-white font-medium">
                            {{ strtoupper(substr($space->name, 0, 1)) }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-medium text-white group-hover:text-confluence-blue truncate">{{ $space->name }}</h3>
                            <p class="text-xs text-confluence-textMuted">{{ $space->key }}</p>
                        </div>
                    </a>
                    <form action="{{ route('spaces.star', $space) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="{{ auth()->user()->hasStarred($space) ? 'text-confluence-yellow' : 'text-confluence-textMuted hover:text-confluence-yellow opacity-0 group-hover:opacity-100' }} transition">
                            <i class="fas fa-star"></i>
                        </button>
                    </form>
                </div>
                <p class="text-sm text-confluence-textMuted mb-3 line-clamp-2">{{ $space->description ?: 'No description' }}</p>
                <div class="flex items-center justify-between text-xs text-confluence-textMuted">
                    <span class="flex items-center">
                        <div class="w-5 h-5 rounded-full bg-confluence-green flex items-center justify-center text-white text-[10px] mr-1">
                            {{ strtoupper(substr($space->owner->name ?? 'U', 0, 1)) }}
                        </div>
                        {{ $space->owner->name ?? 'Unknown' }}
                    </span>
                    <span><i class="fas fa-file-alt mr-1"></i>{{ $space->pages_count ?? $space->pages->count() }}</span>
                </div>
                <div class="mt-3 pt-3 border-t border-confluence-border flex items-center space-x-3">
                    <a href="{{ route('spaces.show', $space) }}" class="text-confluence-blue hover:text-confluence-blueHover text-sm">
                        Open
                    </a>
                    @can('update', $space)
                        <a href="{{ route('spaces.edit', $space) }}" class="text-confluence-textMuted hover:text-confluence-text text-sm">
                            Settings
                        </a>
                    @endcan
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-confluence-card border border-confluence-border rounded-lg p-12 text-center">
                <i class="fas fa-folder-open text-5xl text-confluence-textMuted mb-4"></i>
                <h3 class="text-lg font-medium text-white mb-2">No spaces yet</h3>
                <p class="text-confluence-textMuted mb-6">Spaces help you organize your pages and collaborate with your team.</p>
                <a href="{{ route('spaces.create') }}" class="inline-flex items-center px-4 py-2 bg-confluence-blue hover:bg-confluence-blueHover text-white rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>Create your first space
                </a>
            </div>
        @endforelse
    </div>

    @if($spaces->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $spaces->links() }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

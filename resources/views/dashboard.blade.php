@extends('layouts.app')

@section('title', 'For you - Confluence')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8">
    <!-- Pick up where you left off -->
    <section class="mb-8">
        <h2 class="text-lg font-semibold text-white mb-4">Pick up where you left off</h2>
        <div class="bg-confluence-card border border-confluence-border rounded-lg p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-base font-medium text-white mb-2">Get out there and explore</h3>
                    <p class="text-sm text-confluence-textMuted mb-4">Once you visit, edit, or create pages or live docs, you'll find the most recent ones here.</p>
                    <a href="{{ route('pages.create') }}" class="inline-flex items-center px-4 py-2 border border-confluence-border rounded-md text-sm font-medium text-confluence-text hover:bg-confluence-sidebar transition">
                        Create a live doc
                    </a>
                </div>
                <div class="ml-6 hidden md:block">
                    <div class="w-48 h-32 bg-confluence-sidebar rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-12 h-12 mx-auto bg-confluence-blue/20 rounded-lg flex items-center justify-center mb-2">
                                <i class="fas fa-play text-confluence-blue text-xl"></i>
                            </div>
                            <div class="flex space-x-1 justify-center">
                                <div class="w-8 h-6 bg-confluence-purple/30 rounded"></div>
                                <div class="w-8 h-6 bg-confluence-green/30 rounded"></div>
                                <div class="w-8 h-6 bg-confluence-yellow/30 rounded"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Discover what's happening -->
    <section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">Discover what's happening</h2>
            <div class="flex items-center space-x-3">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center text-sm text-confluence-textMuted hover:text-confluence-text">
                        Sort by: <span class="ml-1 text-confluence-text">Most relevant</span>
                        <i class="fas fa-chevron-down ml-2 text-xs"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-confluence-card border border-confluence-border rounded-md shadow-lg py-1 z-50">
                        <a href="?sort=relevant" class="block px-4 py-2 text-sm hover:bg-confluence-sidebar">Most relevant</a>
                        <a href="?sort=recent" class="block px-4 py-2 text-sm hover:bg-confluence-sidebar">Most recent</a>
                    </div>
                </div>
                <button class="text-sm text-confluence-textMuted hover:text-confluence-text">
                    Edit feed
                </button>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex items-center space-x-2 mb-4">
            <a href="?filter=following" class="inline-flex items-center px-3 py-1.5 rounded-full text-sm {{ request('filter', 'following') === 'following' ? 'bg-confluence-blue/20 text-confluence-blue border border-confluence-blue/30' : 'bg-confluence-card border border-confluence-border text-confluence-text hover:bg-confluence-sidebar' }}">
                <i class="fas fa-users mr-2"></i>Following
            </a>
            <a href="?filter=popular" class="inline-flex items-center px-3 py-1.5 rounded-full text-sm {{ request('filter') === 'popular' ? 'bg-confluence-blue/20 text-confluence-blue border border-confluence-blue/30' : 'bg-confluence-card border border-confluence-border text-confluence-text hover:bg-confluence-sidebar' }}">
                <i class="fas fa-chart-line mr-2"></i>Popular
            </a>
            <a href="?filter=announcements" class="inline-flex items-center px-3 py-1.5 rounded-full text-sm {{ request('filter') === 'announcements' ? 'bg-confluence-blue/20 text-confluence-blue border border-confluence-blue/30' : 'bg-confluence-card border border-confluence-border text-confluence-text hover:bg-confluence-sidebar' }}">
                <i class="fas fa-bullhorn mr-2"></i>Announcements
            </a>
            <a href="?filter=calendars" class="inline-flex items-center px-3 py-1.5 rounded-full text-sm {{ request('filter') === 'calendars' ? 'bg-confluence-blue/20 text-confluence-blue border border-confluence-blue/30' : 'bg-confluence-card border border-confluence-border text-confluence-text hover:bg-confluence-sidebar' }}">
                <i class="fas fa-calendar mr-2"></i>Calendars
            </a>
        </div>

        <!-- AI Summary Note -->
        <div class="flex items-center text-xs text-confluence-textMuted mb-4">
            <i class="fas fa-info-circle mr-2"></i>
            <span>Summaries use AI. Verify results.</span>
            <div class="ml-3 flex items-center space-x-2">
                <button class="hover:text-confluence-text"><i class="fas fa-thumbs-up"></i></button>
                <button class="hover:text-confluence-text"><i class="fas fa-thumbs-down"></i></button>
            </div>
        </div>

        <!-- Activity Feed -->
        <div class="space-y-4">
            @forelse($recentPages as $page)
                <div class="feed-card bg-confluence-card border border-confluence-border rounded-lg p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-confluence-purple flex items-center justify-center text-white text-xs font-medium mr-3">
                                {{ strtoupper(substr($page->author->name ?? 'U', 0, 1)) }}{{ strtoupper(substr(explode(' ', $page->author->name ?? 'User')[1] ?? '', 0, 1)) }}
                            </div>
                            <div>
                                <span class="text-sm font-medium text-white">{{ $page->author->name ?? 'Unknown' }}</span>
                                <span class="text-sm text-confluence-textMuted">edited</span>
                                <span class="text-sm text-confluence-textMuted">{{ $page->updated_at->format('F j, Y') }}</span>
                            </div>
                        </div>
                        <span class="text-xs text-confluence-textMuted">Recent actions</span>
                    </div>

                    <div class="border-t border-confluence-border pt-3">
                        <a href="{{ route('pages.show', $page) }}" class="flex items-start group">
                            <i class="fas fa-file-alt text-confluence-blue mr-3 mt-1"></i>
                            <div class="flex-1">
                                <h3 class="text-base font-medium text-confluence-blue group-hover:text-confluence-blueHover">{{ $page->title }}</h3>
                                <p class="text-sm text-confluence-textMuted mt-1">
                                    Owned by <span class="text-confluence-blue">{{ $page->author->name ?? 'Unknown' }}</span>
                                    @if($page->space)
                                        <span class="mx-1">&bull;</span>
                                        <a href="{{ route('spaces.show', $page->space) }}" class="text-confluence-blue hover:text-confluence-blueHover">{{ $page->space->name }}</a>
                                    @endif
                                </p>
                                <p class="text-sm text-confluence-text mt-2 line-clamp-2">
                                    {{ Str::limit(strip_tags($page->content), 200) }}
                                </p>
                            </div>
                        </a>

                        <div class="flex items-center mt-3 pt-3 border-t border-confluence-border">
                            <form action="{{ route('pages.like', $page) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="flex items-center text-sm {{ auth()->user()->hasLiked($page) ? 'text-confluence-yellow' : 'text-confluence-textMuted hover:text-confluence-text' }}">
                                    <i class="fas fa-thumbs-up mr-1"></i>
                                    <span>{{ $page->likes_count }}</span>
                                </button>
                            </form>
                            <button class="ml-4 text-confluence-textMuted hover:text-confluence-text">
                                <i class="fas fa-smile"></i>
                            </button>
                            <span class="ml-auto text-xs text-confluence-textMuted flex items-center">
                                <i class="fas fa-eye mr-1"></i>{{ $page->view_count ?? 0 }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-confluence-card border border-confluence-border rounded-lg p-8 text-center">
                    <i class="fas fa-inbox text-4xl text-confluence-textMuted mb-4"></i>
                    <h3 class="text-lg font-medium text-white mb-2">No activity yet</h3>
                    <p class="text-confluence-textMuted mb-4">Start by creating a page or following some spaces.</p>
                    <a href="{{ route('pages.create') }}" class="inline-flex items-center px-4 py-2 bg-confluence-blue hover:bg-confluence-blueHover text-white rounded-md text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i>Create your first page
                    </a>
                </div>
            @endforelse
        </div>
    </section>
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

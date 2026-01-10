@extends('layouts.app')

@section('title', 'Search - Confluence')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-white mb-4">Search</h1>
        <form action="{{ route('search') }}" method="GET">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-confluence-textMuted"></i>
                <input type="text" name="q" value="{{ $query }}"
                       placeholder="Search pages, spaces, and comments..."
                       autofocus
                       class="w-full bg-confluence-card border border-confluence-border rounded-lg pl-12 pr-4 py-3 text-base focus:outline-none focus:border-confluence-blue focus:ring-1 focus:ring-confluence-blue">
            </div>
        </form>
    </div>

    @if($query)
        <!-- Filter Tabs -->
        <div class="flex items-center space-x-4 mb-6 border-b border-confluence-border pb-4">
            <button class="text-confluence-blue border-b-2 border-confluence-blue pb-2 text-sm font-medium">All</button>
            <button class="text-confluence-textMuted hover:text-confluence-text pb-2 text-sm">Pages</button>
            <button class="text-confluence-textMuted hover:text-confluence-text pb-2 text-sm">Spaces</button>
            <button class="text-confluence-textMuted hover:text-confluence-text pb-2 text-sm">People</button>
        </div>

        <div class="mb-4">
            <p class="text-sm text-confluence-textMuted">
                Found <span class="text-white font-medium">{{ $results->count() }}</span> result(s) for "<span class="text-white">{{ $query }}</span>"
            </p>
        </div>

        @if($results->count() > 0)
            <div class="space-y-3">
                @foreach($results as $result)
                    <a href="{{ $result['url'] }}" class="block bg-confluence-card border border-confluence-border rounded-lg p-4 hover:border-confluence-blue/50 transition group">
                        <div class="flex items-start">
                            <div class="mr-4">
                                @if($result['type'] === 'page')
                                    <div class="w-10 h-10 rounded bg-confluence-blue/20 flex items-center justify-center">
                                        <i class="fas fa-file-alt text-confluence-blue"></i>
                                    </div>
                                @elseif($result['type'] === 'space')
                                    <div class="w-10 h-10 rounded bg-confluence-purple flex items-center justify-center text-white font-medium">
                                        {{ strtoupper(substr($result['title'], 0, 1)) }}
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded bg-confluence-green/20 flex items-center justify-center">
                                        <i class="fas fa-comment text-confluence-green"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center mb-1">
                                    <span class="text-xs bg-confluence-sidebar text-confluence-textMuted px-2 py-0.5 rounded mr-2 uppercase">
                                        {{ $result['type'] }}
                                    </span>
                                    <h3 class="font-medium text-white group-hover:text-confluence-blue truncate">
                                        {{ $result['title'] }}
                                    </h3>
                                </div>
                                <p class="text-sm text-confluence-textMuted line-clamp-2 mb-2">{{ $result['description'] }}</p>
                                <div class="flex items-center text-xs text-confluence-textMuted">
                                    @if(isset($result['space']))
                                        <span class="flex items-center mr-4">
                                            <i class="fas fa-folder mr-1"></i>{{ $result['space'] }}
                                        </span>
                                    @endif
                                    <span class="flex items-center">
                                        <i class="fas fa-user mr-1"></i>{{ $result['author'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-confluence-card border border-confluence-border rounded-lg p-12 text-center">
                <i class="fas fa-search text-4xl text-confluence-textMuted mb-4"></i>
                <h3 class="text-lg font-medium text-white mb-2">No results found</h3>
                <p class="text-confluence-textMuted">Try adjusting your search terms or filters</p>
            </div>
        @endif
    @else
        <div class="bg-confluence-card border border-confluence-border rounded-lg p-12 text-center">
            <i class="fas fa-search text-4xl text-confluence-textMuted mb-4"></i>
            <h3 class="text-lg font-medium text-white mb-2">Start searching</h3>
            <p class="text-confluence-textMuted">Enter keywords to search across pages, spaces, and comments</p>
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

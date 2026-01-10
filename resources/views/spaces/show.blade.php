@extends('layouts.app')

@section('title', $space->name . ' - Confluence')

@section('content')
<div class="flex h-full" data-space-id="{{ $space->id }}">
    <!-- Space Sidebar -->
    <aside class="w-72 bg-confluence-sidebar border-r border-confluence-border flex-shrink-0 overflow-y-auto scrollbar-thin">
        <!-- Space Header -->
        <div class="p-4 border-b border-confluence-border">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="w-8 h-8 rounded bg-confluence-purple flex items-center justify-center text-white font-medium">
                        {{ strtoupper(substr($space->name, 0, 1)) }}
                    </span>
                    <div>
                        <h2 class="font-medium text-white text-sm">{{ $space->name }}</h2>
                        <span class="text-xs text-confluence-textMuted">{{ $space->key }}</span>
                    </div>
                </div>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="p-1 rounded hover:bg-confluence-card text-confluence-textMuted">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-confluence-card border border-confluence-border rounded-md shadow-lg py-1 z-50">
                        @can('update', $space)
                            <a href="{{ route('spaces.edit', $space) }}" class="block px-4 py-2 text-sm hover:bg-confluence-sidebar">
                                <i class="fas fa-cog w-5 mr-2"></i>Space settings
                            </a>
                        @endcan
                        <form action="{{ route('spaces.star', $space) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-confluence-sidebar">
                                <i class="fas fa-star w-5 mr-2"></i>{{ auth()->user()->hasStarred($space) ? 'Unstar' : 'Star' }} space
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="p-2">
            <a href="{{ route('spaces.show', $space) }}" class="sidebar-item active flex items-center px-3 py-2 rounded-md text-sm mb-1">
                <i class="fas fa-home w-5 mr-2"></i>Overview
            </a>

            <div class="mt-4 mb-2 px-3">
                <span class="text-xs font-medium text-confluence-textMuted uppercase">Pages</span>
            </div>

            <!-- Page Tree -->
            @foreach($pages as $page)
                <div class="mb-1" x-data="{ open: false }">
                    <a href="{{ route('pages.show', $page) }}" class="flex items-center px-3 py-1.5 rounded text-sm text-confluence-text hover:bg-confluence-card">
                        @if($page->children->count() > 0)
                            <button @click.prevent="open = !open" class="mr-1">
                                <i class="fas fa-chevron-right text-xs opacity-50 transition-transform" :class="open ? 'rotate-90' : ''"></i>
                            </button>
                        @else
                            <i class="fas fa-file-alt text-xs mr-2 opacity-50"></i>
                        @endif
                        <span class="truncate">{{ $page->title }}</span>
                    </a>
                    @if($page->children->count() > 0)
                        <div x-show="open" x-collapse class="ml-4">
                            @foreach($page->children as $child)
                                <a href="{{ route('pages.show', $child) }}" class="flex items-center px-3 py-1.5 rounded text-sm text-confluence-text hover:bg-confluence-card">
                                    <i class="fas fa-file-alt text-xs mr-2 opacity-50"></i>
                                    <span class="truncate">{{ $child->title }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach

            @if($pages->count() === 0)
                <p class="px-3 py-2 text-sm text-confluence-textMuted">No pages yet</p>
            @endif
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">
        <!-- Header -->
        <div class="sticky top-0 bg-confluence-bg border-b border-confluence-border px-6 py-3 flex items-center justify-between z-10">
            <div class="flex items-center text-sm">
                <a href="{{ route('spaces.index') }}" class="text-confluence-textMuted hover:text-confluence-text">Spaces</a>
                <i class="fas fa-chevron-right mx-2 text-xs text-confluence-textMuted"></i>
                <span class="text-white">{{ $space->name }}</span>
            </div>
            <div class="flex items-center space-x-2">
                <form action="{{ route('spaces.star', $space) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="p-2 rounded hover:bg-confluence-card {{ auth()->user()->hasStarred($space) ? 'text-confluence-yellow' : 'text-confluence-textMuted hover:text-confluence-text' }}">
                        <i class="fas fa-star"></i>
                    </button>
                </form>
                <a href="{{ route('pages.create', ['space' => $space->id]) }}" class="bg-confluence-blue hover:bg-confluence-blueHover text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                    <i class="fas fa-plus mr-2"></i>Create
                </a>
            </div>
        </div>

        <!-- Space Overview -->
        <div class="max-w-4xl mx-auto px-6 py-8">
            <div class="mb-8">
                <div class="flex items-center space-x-4 mb-4">
                    <span class="w-16 h-16 rounded-lg bg-confluence-purple flex items-center justify-center text-white text-2xl font-medium">
                        {{ strtoupper(substr($space->name, 0, 1)) }}
                    </span>
                    <div>
                        <h1 class="text-3xl font-semibold text-white">{{ $space->name }}</h1>
                        <p class="text-confluence-textMuted">{{ $space->key }}</p>
                    </div>
                </div>
                @if($space->description)
                    <p class="text-confluence-text">{{ $space->description }}</p>
                @endif
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="bg-confluence-card border border-confluence-border rounded-lg p-4">
                    <div class="text-2xl font-semibold text-white mb-1">{{ $pages->count() }}</div>
                    <div class="text-sm text-confluence-textMuted">Pages</div>
                </div>
                <div class="bg-confluence-card border border-confluence-border rounded-lg p-4">
                    <div class="text-2xl font-semibold text-white mb-1">{{ $space->owner->name ?? 'Unknown' }}</div>
                    <div class="text-sm text-confluence-textMuted">Owner</div>
                </div>
                <div class="bg-confluence-card border border-confluence-border rounded-lg p-4">
                    <div class="text-2xl font-semibold text-white mb-1">{{ $space->created_at->format('M j, Y') }}</div>
                    <div class="text-sm text-confluence-textMuted">Created</div>
                </div>
            </div>

            <!-- Pages List -->
            <div class="bg-confluence-card border border-confluence-border rounded-lg">
                <div class="px-4 py-3 border-b border-confluence-border flex items-center justify-between">
                    <h2 class="font-medium text-white">Pages in this space</h2>
                    <a href="{{ route('pages.create', ['space' => $space->id]) }}" class="text-confluence-blue hover:text-confluence-blueHover text-sm">
                        <i class="fas fa-plus mr-1"></i>Add page
                    </a>
                </div>
                @if($pages->count() > 0)
                    <div class="divide-y divide-confluence-border">
                        @foreach($pages as $page)
                            <a href="{{ route('pages.show', $page) }}" class="flex items-center px-4 py-3 hover:bg-confluence-sidebar group">
                                <i class="fas fa-file-alt text-confluence-textMuted mr-3"></i>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-medium text-white group-hover:text-confluence-blue truncate">{{ $page->title }}</h3>
                                    <p class="text-xs text-confluence-textMuted">Updated {{ $page->updated_at->diffForHumans() }} by {{ $page->author->name ?? 'Unknown' }}</p>
                                </div>
                                <div class="flex items-center space-x-3 text-xs text-confluence-textMuted">
                                    <span><i class="fas fa-eye mr-1"></i>{{ $page->view_count ?? 0 }}</span>
                                    <span><i class="fas fa-thumbs-up mr-1"></i>{{ $page->likes_count }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center">
                        <i class="fas fa-file-alt text-4xl text-confluence-textMuted mb-4"></i>
                        <h3 class="text-lg font-medium text-white mb-2">No pages yet</h3>
                        <p class="text-confluence-textMuted mb-4">Create the first page in this space.</p>
                        <a href="{{ route('pages.create', ['space' => $space->id]) }}" class="inline-flex items-center px-4 py-2 bg-confluence-blue hover:bg-confluence-blueHover text-white rounded-md text-sm font-medium">
                            <i class="fas fa-plus mr-2"></i>Create page
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.body.dataset.spaceId = '{{ $space->id }}';
</script>
@endsection

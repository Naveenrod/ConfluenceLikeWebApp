@extends('layouts.app')

@section('title', $page->title . ' - Confluence')

@section('content')
<div class="flex h-full" data-page-id="{{ $page->id }}">
    <!-- Page Tree Sidebar -->
    <aside class="w-72 bg-confluence-sidebar border-r border-confluence-border flex-shrink-0 overflow-y-auto scrollbar-thin">
        <!-- Space Header -->
        <div class="p-4 border-b border-confluence-border">
            <a href="{{ route('spaces.show', $page->space) }}" class="flex items-center space-x-2 group">
                <span class="w-6 h-6 rounded bg-confluence-purple flex items-center justify-center text-xs text-white">
                    {{ strtoupper(substr($page->space->name, 0, 1)) }}
                </span>
                <span class="font-medium text-white group-hover:text-confluence-blue truncate">{{ $page->space->name }}</span>
            </a>
        </div>

        <!-- Page Tree -->
        <nav class="p-2">
            @php
                function renderPageTree($pages, $currentPageId, $depth = 0) {
                    foreach ($pages as $p) {
                        $isActive = $p->id === $currentPageId;
                        $hasChildren = $p->children->count() > 0;
                        $paddingLeft = ($depth * 16) + 8;
                        echo '<div class="mb-1">';
                        echo '<a href="' . route('pages.show', $p) . '" class="flex items-center px-2 py-1.5 rounded text-sm ' . ($isActive ? 'bg-confluence-blue/20 text-confluence-blue' : 'text-confluence-text hover:bg-confluence-card') . '" style="padding-left: ' . $paddingLeft . 'px">';
                        if ($hasChildren) {
                            echo '<i class="fas fa-chevron-right text-xs mr-2 opacity-50"></i>';
                        } else {
                            echo '<i class="fas fa-file-alt text-xs mr-2 opacity-50"></i>';
                        }
                        echo '<span class="truncate">' . e($p->title) . '</span>';
                        echo '</a>';
                        if ($hasChildren) {
                            renderPageTree($p->children, $currentPageId, $depth + 1);
                        }
                        echo '</div>';
                    }
                }
                $rootPages = $page->space->rootPages()->with('children')->get();
            @endphp
            @foreach($rootPages as $rootPage)
                <div class="mb-1" x-data="{ open: {{ $rootPage->id === $page->id || $page->breadcrumbs->pluck('id')->contains($rootPage->id) ? 'true' : 'false' }} }">
                    <a href="{{ route('pages.show', $rootPage) }}" class="flex items-center px-2 py-1.5 rounded text-sm {{ $rootPage->id === $page->id ? 'bg-confluence-blue/20 text-confluence-blue' : 'text-confluence-text hover:bg-confluence-card' }}">
                        @if($rootPage->children->count() > 0)
                            <button @click.prevent="open = !open" class="mr-1">
                                <i class="fas fa-chevron-right text-xs opacity-50 transition-transform" :class="open ? 'rotate-90' : ''"></i>
                            </button>
                        @else
                            <i class="fas fa-file-alt text-xs mr-2 opacity-50"></i>
                        @endif
                        <span class="truncate">{{ $rootPage->title }}</span>
                    </a>
                    @if($rootPage->children->count() > 0)
                        <div x-show="open" x-collapse class="ml-4">
                            @foreach($rootPage->children as $child)
                                <a href="{{ route('pages.show', $child) }}" class="flex items-center px-2 py-1.5 rounded text-sm {{ $child->id === $page->id ? 'bg-confluence-blue/20 text-confluence-blue' : 'text-confluence-text hover:bg-confluence-card' }}">
                                    <i class="fas fa-file-alt text-xs mr-2 opacity-50"></i>
                                    <span class="truncate">{{ $child->title }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">
        <!-- Page Header -->
        <div class="sticky top-0 bg-confluence-bg border-b border-confluence-border px-6 py-3 flex items-center justify-between z-10">
            <div class="flex items-center text-sm text-confluence-textMuted">
                <span>Edited {{ $page->updated_at->format('M d') }}</span>
            </div>
            <div class="flex items-center space-x-2">
                <!-- Star -->
                <form action="{{ route('pages.star', $page) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="p-2 rounded hover:bg-confluence-card {{ auth()->user()->hasStarred($page) ? 'text-confluence-yellow' : 'text-confluence-textMuted hover:text-confluence-text' }}">
                        <i class="fas fa-star"></i>
                    </button>
                </form>

                <!-- Share -->
                <div x-data="{ showShare: false, shareUrl: '', copied: false }">
                    <button @click="showShare = true" class="p-2 rounded hover:bg-confluence-card text-confluence-textMuted hover:text-confluence-text flex items-center">
                        <i class="fas fa-share-alt mr-1"></i>
                        <span class="text-sm">Share</span>
                    </button>

                    <!-- Share Modal -->
                    <div x-show="showShare" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showShare = false">
                        <div class="bg-confluence-card border border-confluence-border rounded-lg p-6 w-96">
                            <h3 class="text-lg font-medium text-white mb-4">Share this page</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm text-confluence-textMuted mb-2">Page URL</label>
                                    <div class="flex">
                                        <input type="text" value="{{ url()->current() }}" readonly class="flex-1 bg-confluence-sidebar border border-confluence-border rounded-l px-3 py-2 text-sm">
                                        <button @click="navigator.clipboard.writeText('{{ url()->current() }}'); copied = true; setTimeout(() => copied = false, 2000)" class="px-4 py-2 bg-confluence-blue hover:bg-confluence-blueHover text-white rounded-r text-sm">
                                            <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                                        </button>
                                    </div>
                                </div>
                                <div class="border-t border-confluence-border pt-4">
                                    <p class="text-sm text-confluence-textMuted mb-2">Create a shareable link</p>
                                    <form action="{{ route('pages.share.create', $page) }}" method="POST" @submit.prevent="
                                        fetch('{{ route('pages.share.create', $page) }}', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Accept': 'application/json'
                                            }
                                        })
                                        .then(r => r.json())
                                        .then(data => { shareUrl = data.url; })
                                    ">
                                        <button type="submit" class="w-full px-4 py-2 border border-confluence-border rounded hover:bg-confluence-sidebar text-sm">
                                            Generate shareable link
                                        </button>
                                    </form>
                                    <div x-show="shareUrl" class="mt-3">
                                        <input type="text" x-model="shareUrl" readonly class="w-full bg-confluence-sidebar border border-confluence-border rounded px-3 py-2 text-sm">
                                    </div>
                                </div>
                            </div>
                            <button @click="showShare = false" class="mt-4 w-full px-4 py-2 bg-confluence-sidebar hover:bg-confluence-card rounded text-sm">
                                Close
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Link -->
                <button onclick="navigator.clipboard.writeText(window.location.href)" class="p-2 rounded hover:bg-confluence-card text-confluence-textMuted hover:text-confluence-text">
                    <i class="fas fa-link"></i>
                </button>

                <!-- More Actions -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="p-2 rounded hover:bg-confluence-card text-confluence-textMuted hover:text-confluence-text">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-confluence-card border border-confluence-border rounded-md shadow-lg py-1 z-50">
                        @can('update', $page)
                            <a href="{{ route('pages.edit', $page) }}" class="block px-4 py-2 text-sm hover:bg-confluence-sidebar">
                                <i class="fas fa-edit w-5 mr-2"></i>Edit
                            </a>
                        @endcan
                        <a href="{{ route('pages.versions', $page) }}" class="block px-4 py-2 text-sm hover:bg-confluence-sidebar">
                            <i class="fas fa-history w-5 mr-2"></i>Page history
                        </a>
                        @can('delete', $page)
                            <form action="{{ route('pages.destroy', $page) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-confluence-sidebar text-red-400">
                                    <i class="fas fa-trash w-5 mr-2"></i>Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="max-w-4xl mx-auto px-6 py-8">
            <!-- Page Icon & Title -->
            <div class="mb-6">
                <div class="flex items-center mb-2">
                    <i class="fas fa-file-alt text-2xl text-confluence-textMuted mr-3"></i>
                    <h1 class="text-3xl font-semibold text-white">{{ $page->title }}</h1>
                </div>
                <div class="flex items-center text-sm text-confluence-textMuted space-x-4">
                    <span class="flex items-center">
                        By <span class="text-confluence-text ml-1">{{ $page->author->name ?? 'Unknown' }}</span>
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-chart-line mr-1"></i>{{ $page->view_count ?? 0 }}
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-thumbs-up mr-1"></i>{{ $page->likes_count }}
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="prose prose-invert max-w-none mb-8">
                <div class="text-confluence-text leading-relaxed">
                    {!! $page->content !!}
                </div>
            </div>

            <!-- Child Pages -->
            @if($page->children->count() > 0)
                <div class="mb-8 p-4 bg-confluence-card border border-confluence-border rounded-lg">
                    <h3 class="text-sm font-medium text-confluence-textMuted uppercase mb-3">Child Pages</h3>
                    <div class="space-y-2">
                        @foreach($page->children as $child)
                            <a href="{{ route('pages.show', $child) }}" class="flex items-center text-confluence-blue hover:text-confluence-blueHover">
                                <i class="fas fa-file-alt mr-2 text-sm"></i>{{ $child->title }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Attachments -->
            <div class="mb-8 p-4 bg-confluence-card border border-confluence-border rounded-lg">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-confluence-textMuted uppercase">Attachments</h3>
                    @can('update', $page)
                        <form action="{{ route('attachments.store', $page) }}" method="POST" enctype="multipart/form-data" class="flex items-center" x-data="{ fileName: '' }">
                            @csrf
                            <label class="cursor-pointer px-3 py-1 bg-confluence-sidebar hover:bg-confluence-card border border-confluence-border rounded text-sm">
                                <i class="fas fa-plus mr-1"></i>Add
                                <input type="file" name="file" class="hidden" @change="fileName = $event.target.files[0]?.name; if(fileName) $el.closest('form').submit()">
                            </label>
                        </form>
                    @endcan
                </div>
                @if($page->attachments->count() > 0)
                    <div class="space-y-2">
                        @foreach($page->attachments as $attachment)
                            <div class="flex items-center justify-between py-2 border-b border-confluence-border last:border-0">
                                <a href="{{ route('attachments.download', $attachment) }}" class="flex items-center text-confluence-blue hover:text-confluence-blueHover">
                                    <i class="fas fa-paperclip mr-2"></i>
                                    <span>{{ $attachment->original_filename }}</span>
                                    <span class="ml-2 text-xs text-confluence-textMuted">({{ number_format($attachment->size / 1024, 1) }} KB)</span>
                                </a>
                                @can('update', $page)
                                    <form action="{{ route('attachments.destroy', $attachment) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-sm" onclick="return confirm('Delete this attachment?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-confluence-textMuted">No attachments</p>
                @endif
            </div>

            <!-- Like & React Section -->
            <div class="flex items-center space-x-4 mb-8 pb-8 border-b border-confluence-border">
                <form action="{{ route('pages.like', $page) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-2 rounded-full border {{ auth()->user()->hasLiked($page) ? 'border-confluence-yellow text-confluence-yellow bg-confluence-yellow/10' : 'border-confluence-border text-confluence-text hover:bg-confluence-card' }}">
                        <i class="fas fa-thumbs-up mr-2"></i>
                        <span>{{ $page->likes_count }}</span>
                    </button>
                </form>
                <button class="p-2 rounded-full border border-confluence-border text-confluence-textMuted hover:bg-confluence-card">
                    <i class="fas fa-smile"></i>
                </button>
            </div>

            <!-- Comments -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-white mb-4">Comments</h3>

                <!-- Comment Form -->
                <form action="{{ route('comments.store', $page) }}" method="POST" class="mb-6">
                    @csrf
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-full bg-confluence-purple flex items-center justify-center text-white text-xs font-medium flex-shrink-0">
                            {{ auth()->user()->initials }}
                        </div>
                        <div class="flex-1">
                            <textarea name="content" rows="3" placeholder="Add a comment..." class="w-full bg-confluence-card border border-confluence-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-confluence-blue resize-none"></textarea>
                            <div class="mt-2 flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-confluence-blue hover:bg-confluence-blueHover text-white rounded text-sm font-medium">
                                    Post
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Comments List -->
                <div class="space-y-4">
                    @foreach($page->comments->where('parent_id', null) as $comment)
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-confluence-green flex items-center justify-center text-white text-xs font-medium flex-shrink-0">
                                {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}{{ strtoupper(substr(explode(' ', $comment->user->name ?? 'User')[1] ?? '', 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="font-medium text-white text-sm">{{ $comment->user->name ?? 'Unknown' }}</span>
                                    <span class="text-xs text-confluence-textMuted">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-confluence-text">{{ $comment->content }}</p>
                                <div class="mt-2 flex items-center space-x-4 text-xs text-confluence-textMuted">
                                    <button class="hover:text-confluence-text">Reply</button>
                                    @can('update', $comment)
                                        <button class="hover:text-confluence-text">Edit</button>
                                    @endcan
                                    @can('delete', $comment)
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="hover:text-red-400" onclick="return confirm('Delete this comment?')">Delete</button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar (optional) -->
    <aside class="w-12 bg-confluence-sidebar border-l border-confluence-border flex-shrink-0 flex flex-col items-center py-4 space-y-4">
        <button class="p-2 rounded hover:bg-confluence-card text-confluence-textMuted hover:text-confluence-text" title="Comments">
            <i class="fas fa-comment"></i>
        </button>
        <button class="p-2 rounded hover:bg-confluence-card text-confluence-textMuted hover:text-confluence-text" title="Info">
            <i class="fas fa-info-circle"></i>
        </button>
        <button class="p-2 rounded hover:bg-confluence-card text-confluence-textMuted hover:text-confluence-text" title="Copy">
            <i class="fas fa-copy"></i>
        </button>
        <button class="p-2 rounded hover:bg-confluence-card text-confluence-textMuted hover:text-confluence-text" title="Video">
            <i class="fas fa-video"></i>
        </button>
        <button class="p-2 rounded hover:bg-confluence-card text-confluence-textMuted hover:text-confluence-text" title="AI">
            <i class="fas fa-robot"></i>
        </button>
    </aside>
</div>

<script>
    document.body.dataset.pageId = '{{ $page->id }}';
</script>
@endsection

@push('styles')
<style>
    .prose-invert { color: #b6c2cf; }
    .prose-invert h1, .prose-invert h2, .prose-invert h3, .prose-invert h4 { color: white; }
    .prose-invert a { color: #579dff; }
    .prose-invert strong { color: white; }
    .prose-invert code { background: #22272b; color: #b6c2cf; padding: 0.2em 0.4em; border-radius: 0.25rem; }
    .prose-invert pre { background: #22272b; }
    .prose-invert blockquote { border-left-color: #579dff; color: #8c9bab; }
    .prose-invert ul, .prose-invert ol { color: #b6c2cf; }
</style>
@endpush

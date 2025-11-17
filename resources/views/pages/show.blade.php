@extends('layouts.app')

@section('title', $page->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumbs -->
    <nav class="mb-4 text-sm">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
            <li><span class="text-gray-500">/</span></li>
            <li><a href="{{ route('spaces.show', $page->space->id) }}" class="text-blue-600 hover:text-blue-800">{{ $page->space->name }}</a></li>
            @foreach($breadcrumbs as $crumb)
                <li><span class="text-gray-500">/</span></li>
                <li>
                    @if($crumb->id === $page->id)
                        <span class="text-gray-900">{{ $crumb->title }}</span>
                    @else
                        <a href="{{ route('pages.show', $crumb->id) }}" class="text-blue-600 hover:text-blue-800">{{ $crumb->title }}</a>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $page->title }}</h1>
                <div class="flex items-center text-sm text-gray-500 space-x-4">
                    <span><i class="fas fa-user mr-1"></i>{{ $page->author->name }}</span>
                    <span><i class="fas fa-clock mr-1"></i>{{ $page->updated_at->diffForHumans() }}</span>
                    <span><i class="fas fa-folder mr-1"></i>{{ $page->space->name }}</span>
                </div>
            </div>
            <div class="flex space-x-2">
                @can('update', $page)
                    <a href="{{ route('pages.edit', $page->id) }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-sm">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @endcan
                <a href="{{ route('pages.versions', $page->id) }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-sm">
                    <i class="fas fa-history mr-2"></i>History
                </a>
            </div>
        </div>

        <div class="prose max-w-none">
            {!! $page->content !!}
        </div>
    </div>

    <!-- Sub-pages -->
    @if($page->children->count() > 0)
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Child Pages</h2>
            <ul class="space-y-2">
                @foreach($page->children as $child)
                    <li>
                        <a href="{{ route('pages.show', $child->id) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-file-alt mr-2"></i>{{ $child->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Attachments -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Attachments</h2>
            @can('update', $page)
                <form action="{{ route('attachments.store', $page->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center">
                    @csrf
                    <input type="file" name="file" required class="mr-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                        <i class="fas fa-upload mr-2"></i>Upload
                    </button>
                </form>
            @endcan
        </div>
        @if($page->attachments->count() > 0)
            <ul class="space-y-2">
                @foreach($page->attachments as $attachment)
                    <li class="flex items-center justify-between border-b pb-2">
                        <div class="flex items-center">
                            <a href="{{ route('attachments.download', $attachment->id) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-file mr-2"></i>{{ $attachment->original_filename }}
                            </a>
                            <span class="ml-4 text-sm text-gray-500">{{ $attachment->human_readable_size }}</span>
                        </div>
                        @can('update', $page)
                            <form action="{{ route('attachments.destroy', $attachment->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm"
                                        onclick="return confirm('Are you sure you want to delete this file?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500">No attachments yet.</p>
        @endif
    </div>

    <!-- Comments -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Comments</h2>
        
        @include('comments.form', ['page' => $page])

        <div class="mt-6 space-y-4">
            @foreach($page->comments->where('parent_id', null) as $comment)
                @include('comments.item', ['comment' => $comment])
            @endforeach
        </div>
    </div>
</div>
@endsection

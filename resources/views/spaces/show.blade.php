@extends('layouts.app')

@section('title', $space->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="{{ $space->icon ?? 'fas fa-folder' }} mr-2"></i>{{ $space->name }}
                </h1>
                @if($space->description)
                    <p class="mt-2 text-gray-600">{{ $space->description }}</p>
                @endif
            </div>
            <div class="flex space-x-2">
                @can('update', $space)
                    <a href="{{ route('spaces.edit', $space->id) }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                        <i class="fas fa-edit mr-2"></i>Edit Space
                    </a>
                @endcan
                <a href="{{ route('pages.create', ['space' => $space->id]) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Create Page
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Pages</h2>
        @if($pages->count() > 0)
            <ul class="space-y-2">
                @foreach($pages as $page)
                    <li class="border-b pb-2">
                        <a href="{{ route('pages.show', $page->id) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-file-alt mr-2"></i>{{ $page->title }}
                            <span class="ml-auto text-sm text-gray-500">by {{ $page->author->name }}</span>
                        </a>
                        @if($page->children->count() > 0)
                            <ul class="ml-6 mt-2 space-y-1">
                                @foreach($page->children as $child)
                                    <li>
                                        <a href="{{ route('pages.show', $child->id) }}" class="text-blue-500 hover:text-blue-700 text-sm">
                                            <i class="fas fa-file mr-1"></i>{{ $child->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500">No pages in this space yet. <a href="{{ route('pages.create', ['space' => $space->id]) }}" class="text-blue-600">Create the first page</a></p>
        @endif
    </div>
</div>
@endsection


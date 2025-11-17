@extends('layouts.app')

@section('title', 'Create Page')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Create New Page</h1>

    <form action="{{ route('pages.store') }}" method="POST" class="bg-white shadow rounded-lg p-6">
        @csrf

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Page Title</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="space_id" class="block text-sm font-medium text-gray-700 mb-2">Space</label>
            <select name="space_id" id="space_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select a space</option>
                @foreach($spaces as $space)
                    <option value="{{ $space->id }}" {{ old('space_id', $spaceId) == $space->id ? 'selected' : '' }}>
                        {{ $space->name }}
                    </option>
                @endforeach
            </select>
            @error('space_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        @if($parent)
            <div class="mb-4">
                <input type="hidden" name="parent_id" value="{{ $parent->id }}">
                <p class="text-sm text-gray-600">Parent page: <strong>{{ $parent->title }}</strong></p>
            </div>
        @else
            <div class="mb-4">
                <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">Parent Page (Optional)</label>
                <select name="parent_id" id="parent_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">None (Root page)</option>
                </select>
                <p class="mt-1 text-sm text-gray-500">Select a parent page to create a hierarchy</p>
            </div>
        @endif

        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
            @include('components.tinymce-editor', ['id' => 'content', 'name' => 'content', 'value' => old('content')])
            @error('content')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ $parent ? route('pages.show', $parent->id) : route('spaces.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Create Page
            </button>
        </div>
    </form>
</div>
@endsection


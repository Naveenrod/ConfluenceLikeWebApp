@extends('layouts.app')

@section('title', 'Edit Page')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Page</h1>

    <form action="{{ route('pages.update', $page->id) }}" method="POST" class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Page Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="space_id" class="block text-sm font-medium text-gray-700 mb-2">Space</label>
            <select name="space_id" id="space_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                @foreach($spaces as $space)
                    <option value="{{ $space->id }}" {{ old('space_id', $page->space_id) == $space->id ? 'selected' : '' }}>
                        {{ $space->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">Parent Page (Optional)</label>
            <select name="parent_id" id="parent_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="">None (Root page)</option>
                @foreach($spaces as $space)
                    @foreach($space->pages->where('id', '!=', $page->id) as $p)
                        <option value="{{ $p->id }}" {{ old('parent_id', $page->parent_id) == $p->id ? 'selected' : '' }}>
                            {{ $space->name }} / {{ $p->title }}
                        </option>
                    @endforeach
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
            @include('components.tinymce-editor', ['id' => 'content', 'name' => 'content', 'value' => old('content', $page->content)])
            @error('content')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="change_summary" class="block text-sm font-medium text-gray-700 mb-2">Change Summary (Optional)</label>
            <input type="text" name="change_summary" id="change_summary" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Brief description of changes">
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('pages.show', $page->id) }}" 
               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Update Page
            </button>
        </div>
    </form>
</div>
@endsection


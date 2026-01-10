@extends('layouts.app')

@section('title', 'Create Page - Confluence')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-white">Create new page</h1>
        <p class="text-sm text-confluence-textMuted mt-1">Add a new page to your space</p>
    </div>

    <form action="{{ route('pages.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Title -->
        <div>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                   placeholder="Page title"
                   class="w-full bg-transparent border-0 border-b border-confluence-border text-2xl font-medium text-white placeholder-confluence-textMuted py-2 focus:outline-none focus:border-confluence-blue">
            @error('title')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Space Selection -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="space_id" class="block text-sm font-medium text-confluence-textMuted mb-2">Space</label>
                <select name="space_id" id="space_id" required
                        class="w-full bg-confluence-card border border-confluence-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-confluence-blue">
                    <option value="">Select a space</option>
                    @foreach($spaces as $space)
                        <option value="{{ $space->id }}" {{ old('space_id', $spaceId) == $space->id ? 'selected' : '' }}>
                            {{ $space->name }}
                        </option>
                    @endforeach
                </select>
                @error('space_id')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                @if($parent)
                    <input type="hidden" name="parent_id" value="{{ $parent->id }}">
                    <label class="block text-sm font-medium text-confluence-textMuted mb-2">Parent page</label>
                    <div class="bg-confluence-card border border-confluence-border rounded-md px-3 py-2 text-sm">
                        <i class="fas fa-file-alt mr-2 text-confluence-textMuted"></i>{{ $parent->title }}
                    </div>
                @else
                    <label for="parent_id" class="block text-sm font-medium text-confluence-textMuted mb-2">Parent page (optional)</label>
                    <select name="parent_id" id="parent_id"
                            class="w-full bg-confluence-card border border-confluence-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-confluence-blue">
                        <option value="">None (Root page)</option>
                    </select>
                @endif
            </div>
        </div>

        <!-- Content Editor -->
        <div>
            <label for="content" class="block text-sm font-medium text-confluence-textMuted mb-2">Content</label>
            <div class="bg-confluence-card border border-confluence-border rounded-lg overflow-hidden">
                @include('components.tinymce-editor', ['id' => 'content', 'name' => 'content', 'value' => old('content')])
            </div>
            @error('content')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between pt-4 border-t border-confluence-border">
            <a href="{{ $parent ? route('pages.show', $parent->id) : ($spaceId ? route('spaces.show', $spaceId) : route('dashboard')) }}"
               class="px-4 py-2 text-sm text-confluence-textMuted hover:text-confluence-text">
                Cancel
            </a>
            <div class="flex items-center space-x-3">
                <button type="submit" name="is_published" value="0" class="px-4 py-2 border border-confluence-border rounded-md text-sm hover:bg-confluence-card">
                    Save as draft
                </button>
                <button type="submit" name="is_published" value="1" class="px-4 py-2 bg-confluence-blue hover:bg-confluence-blueHover text-white rounded-md text-sm font-medium">
                    Publish
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

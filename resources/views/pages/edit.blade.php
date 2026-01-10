@extends('layouts.app')

@section('title', 'Edit: ' . $page->title . ' - Confluence')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8">
    <div class="mb-6">
        <nav class="flex items-center text-sm text-confluence-textMuted mb-2">
            <a href="{{ route('spaces.show', $page->space) }}" class="hover:text-confluence-text">{{ $page->space->name }}</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <a href="{{ route('pages.show', $page) }}" class="hover:text-confluence-text">{{ $page->title }}</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <span class="text-white">Edit</span>
        </nav>
        <h1 class="text-2xl font-semibold text-white">Edit page</h1>
    </div>

    <form action="{{ route('pages.update', $page) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div>
            <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}" required
                   placeholder="Page title"
                   class="w-full bg-transparent border-0 border-b border-confluence-border text-2xl font-medium text-white placeholder-confluence-textMuted py-2 focus:outline-none focus:border-confluence-blue">
            @error('title')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Space & Parent Selection -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="space_id" class="block text-sm font-medium text-confluence-textMuted mb-2">Space</label>
                <select name="space_id" id="space_id" required
                        class="w-full bg-confluence-card border border-confluence-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-confluence-blue">
                    @foreach($spaces as $space)
                        <option value="{{ $space->id }}" {{ old('space_id', $page->space_id) == $space->id ? 'selected' : '' }}>
                            {{ $space->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="parent_id" class="block text-sm font-medium text-confluence-textMuted mb-2">Parent page (optional)</label>
                <select name="parent_id" id="parent_id"
                        class="w-full bg-confluence-card border border-confluence-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-confluence-blue">
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
        </div>

        <!-- Content Editor -->
        <div>
            <label for="content" class="block text-sm font-medium text-confluence-textMuted mb-2">Content</label>
            <div class="bg-confluence-card border border-confluence-border rounded-lg overflow-hidden">
                @include('components.tinymce-editor', ['id' => 'content', 'name' => 'content', 'value' => old('content', $page->content)])
            </div>
            @error('content')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Change Summary -->
        <div>
            <label for="change_summary" class="block text-sm font-medium text-confluence-textMuted mb-2">Change summary (optional)</label>
            <input type="text" name="change_summary" id="change_summary"
                   class="w-full bg-confluence-card border border-confluence-border rounded-md px-4 py-2 text-sm focus:outline-none focus:border-confluence-blue"
                   placeholder="Brief description of your changes">
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between pt-4 border-t border-confluence-border">
            <a href="{{ route('pages.show', $page) }}" class="px-4 py-2 text-sm text-confluence-textMuted hover:text-confluence-text">
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

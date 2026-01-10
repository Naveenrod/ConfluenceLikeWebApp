@extends('layouts.app')

@section('title', 'Create Space - Confluence')

@section('content')
<div class="max-w-2xl mx-auto px-6 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-white">Create new space</h1>
        <p class="text-sm text-confluence-textMuted mt-1">Spaces help you organize your content and collaborate with your team</p>
    </div>

    <form action="{{ route('spaces.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-confluence-card border border-confluence-border rounded-lg p-6 space-y-6">
            <!-- Space Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-confluence-textMuted mb-2">Space name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="w-full bg-confluence-sidebar border border-confluence-border rounded-md px-4 py-3 text-white placeholder-confluence-textMuted focus:outline-none focus:border-confluence-blue focus:ring-1 focus:ring-confluence-blue"
                       placeholder="My awesome space">
                @error('name')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Space Key -->
            <div>
                <label for="key" class="block text-sm font-medium text-confluence-textMuted mb-2">Space key</label>
                <input type="text" name="key" id="key" value="{{ old('key') }}" required
                       class="w-full bg-confluence-sidebar border border-confluence-border rounded-md px-4 py-3 text-white placeholder-confluence-textMuted focus:outline-none focus:border-confluence-blue focus:ring-1 focus:ring-confluence-blue uppercase"
                       placeholder="MYSPACE"
                       oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')">
                <p class="mt-1 text-xs text-confluence-textMuted">A unique identifier (uppercase letters and numbers only)</p>
                @error('key')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-confluence-textMuted mb-2">Description (optional)</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full bg-confluence-sidebar border border-confluence-border rounded-md px-4 py-3 text-white placeholder-confluence-textMuted focus:outline-none focus:border-confluence-blue focus:ring-1 focus:ring-confluence-blue resize-none"
                          placeholder="What is this space about?">{{ old('description') }}</textarea>
            </div>

            <!-- Icon -->
            <div>
                <label class="block text-sm font-medium text-confluence-textMuted mb-2">Space icon</label>
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-lg bg-confluence-purple flex items-center justify-center text-white text-xl" id="icon-preview">
                        <i class="fas fa-folder"></i>
                    </div>
                    <input type="hidden" name="icon" id="icon" value="{{ old('icon', 'fas fa-folder') }}">
                    <div class="flex flex-wrap gap-2">
                        @foreach(['fas fa-folder', 'fas fa-book', 'fas fa-rocket', 'fas fa-code', 'fas fa-cog', 'fas fa-star', 'fas fa-users', 'fas fa-chart-line'] as $iconOption)
                            <button type="button"
                                    onclick="document.getElementById('icon').value = '{{ $iconOption }}'; document.getElementById('icon-preview').innerHTML = '<i class=\'{{ $iconOption }}\'></i>'"
                                    class="w-10 h-10 rounded bg-confluence-sidebar border border-confluence-border hover:border-confluence-blue flex items-center justify-center text-confluence-textMuted hover:text-confluence-blue">
                                <i class="{{ $iconOption }}"></i>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between pt-4">
            <a href="{{ route('spaces.index') }}" class="px-4 py-2 text-sm text-confluence-textMuted hover:text-confluence-text">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-confluence-blue hover:bg-confluence-blueHover text-white rounded-md text-sm font-medium">
                Create space
            </button>
        </div>
    </form>
</div>
@endsection

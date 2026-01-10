@extends('layouts.app')

@section('title', 'Settings: ' . $space->name . ' - Confluence')

@section('content')
<div class="max-w-2xl mx-auto px-6 py-8">
    <div class="mb-6">
        <nav class="flex items-center text-sm text-confluence-textMuted mb-2">
            <a href="{{ route('spaces.show', $space) }}" class="hover:text-confluence-text">{{ $space->name }}</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <span class="text-white">Settings</span>
        </nav>
        <h1 class="text-2xl font-semibold text-white">Space settings</h1>
    </div>

    <form action="{{ route('spaces.update', $space) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-confluence-card border border-confluence-border rounded-lg p-6 space-y-6">
            <!-- Space Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-confluence-textMuted mb-2">Space name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $space->name) }}" required
                       class="w-full bg-confluence-sidebar border border-confluence-border rounded-md px-4 py-3 text-white placeholder-confluence-textMuted focus:outline-none focus:border-confluence-blue focus:ring-1 focus:ring-confluence-blue">
                @error('name')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Space Key -->
            <div>
                <label for="key" class="block text-sm font-medium text-confluence-textMuted mb-2">Space key</label>
                <input type="text" name="key" id="key" value="{{ old('key', $space->key) }}" required
                       class="w-full bg-confluence-sidebar border border-confluence-border rounded-md px-4 py-3 text-white placeholder-confluence-textMuted focus:outline-none focus:border-confluence-blue focus:ring-1 focus:ring-confluence-blue uppercase"
                       oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')">
                @error('key')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-confluence-textMuted mb-2">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full bg-confluence-sidebar border border-confluence-border rounded-md px-4 py-3 text-white placeholder-confluence-textMuted focus:outline-none focus:border-confluence-blue focus:ring-1 focus:ring-confluence-blue resize-none"
                          placeholder="What is this space about?">{{ old('description', $space->description) }}</textarea>
            </div>

            <!-- Icon -->
            <div>
                <label class="block text-sm font-medium text-confluence-textMuted mb-2">Space icon</label>
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-lg bg-confluence-purple flex items-center justify-center text-white text-xl" id="icon-preview">
                        <i class="{{ $space->icon ?? 'fas fa-folder' }}"></i>
                    </div>
                    <input type="hidden" name="icon" id="icon" value="{{ old('icon', $space->icon ?? 'fas fa-folder') }}">
                    <div class="flex flex-wrap gap-2">
                        @foreach(['fas fa-folder', 'fas fa-book', 'fas fa-rocket', 'fas fa-code', 'fas fa-cog', 'fas fa-star', 'fas fa-users', 'fas fa-chart-line'] as $iconOption)
                            <button type="button"
                                    onclick="document.getElementById('icon').value = '{{ $iconOption }}'; document.getElementById('icon-preview').innerHTML = '<i class=\'{{ $iconOption }}\'></i>'"
                                    class="w-10 h-10 rounded bg-confluence-sidebar border border-confluence-border hover:border-confluence-blue flex items-center justify-center text-confluence-textMuted hover:text-confluence-blue {{ ($space->icon ?? 'fas fa-folder') === $iconOption ? 'border-confluence-blue text-confluence-blue' : '' }}">
                                <i class="{{ $iconOption }}"></i>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        @can('delete', $space)
        <div class="bg-red-900/20 border border-red-700/50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-red-400 mb-2">Danger zone</h3>
            <p class="text-sm text-confluence-textMuted mb-4">Once you delete a space, there is no going back. Please be certain.</p>
            <button type="button"
                    onclick="if(confirm('Are you sure you want to delete this space? This action cannot be undone.')) { document.getElementById('delete-form').submit(); }"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm">
                Delete this space
            </button>
        </div>
        @endcan

        <!-- Actions -->
        <div class="flex items-center justify-between pt-4">
            <a href="{{ route('spaces.show', $space) }}" class="px-4 py-2 text-sm text-confluence-textMuted hover:text-confluence-text">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-confluence-blue hover:bg-confluence-blueHover text-white rounded-md text-sm font-medium">
                Save changes
            </button>
        </div>
    </form>

    @can('delete', $space)
    <form id="delete-form" action="{{ route('spaces.destroy', $space) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endcan
</div>
@endsection

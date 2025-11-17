@extends('layouts.app')

@section('title', 'Create Space')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Create New Space</h1>

    <form action="{{ route('spaces.store') }}" method="POST" class="bg-white shadow rounded-lg p-6">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Space Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="key" class="block text-sm font-medium text-gray-700 mb-2">Space Key</label>
            <input type="text" name="key" id="key" value="{{ old('key') }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   placeholder="e.g., PROJECT">
            <p class="mt-1 text-sm text-gray-500">A unique identifier for this space (uppercase letters and numbers only)</p>
            @error('key')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="4"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
        </div>

        <div class="mb-4">
            <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">Icon (Font Awesome class)</label>
            <input type="text" name="icon" id="icon" value="{{ old('icon', 'fas fa-folder') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   placeholder="fas fa-folder">
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('spaces.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Create Space
            </button>
        </div>
    </form>
</div>
@endsection


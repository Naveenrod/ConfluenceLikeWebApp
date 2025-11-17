@extends('layouts.app')

@section('title', 'Version Comparison - ' . $page->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Version Comparison</h1>
        <p class="mt-2 text-sm text-gray-600">
            <a href="{{ route('pages.show', $page->id) }}" class="text-blue-600 hover:text-blue-800">{{ $page->title }}</a>
        </p>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Current Version (v{{ $currentVersion->version_number }})</h2>
            <div class="prose max-w-none">
                {!! $currentVersion->content !!}
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Version {{ $version->version_number }}</h2>
            <div class="prose max-w-none">
                {!! $version->content !!}
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Version {{ $version->version_number }} by {{ $version->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $version->created_at->format('M d, Y H:i') }}</p>
            </div>
            @if($version->version_number != $page->version)
                @can('update', $page)
                    <form action="{{ route('pages.versions.restore', [$page->id, $version->id]) }}" method="POST">
                        @csrf
                        @method('POST')
                        <button type="submit" 
                                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700"
                                onclick="return confirm('Are you sure you want to restore this version?')">
                            Restore This Version
                        </button>
                    </form>
                @endcan
            @endif
        </div>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('title', 'Version History - ' . $page->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Version History</h1>
        <p class="mt-2 text-sm text-gray-600">
            <a href="{{ route('pages.show', $page->id) }}" class="text-blue-600 hover:text-blue-800">{{ $page->title }}</a>
        </p>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Version</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Changed By</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Summary</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($versions as $version)
                    <tr class="{{ $version->version_number == $page->version ? 'bg-blue-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-semibold">v{{ $version->version_number }}</span>
                            @if($version->version_number == $page->version)
                                <span class="ml-2 text-xs bg-blue-600 text-white px-2 py-1 rounded">Current</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $version->user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $version->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $version->change_summary ?? 'No summary' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('pages.versions.show', [$page->id, $version->id]) }}" 
                               class="text-blue-600 hover:text-blue-900">View</a>
                            @if($version->version_number != $page->version)
                                @can('update', $page)
                                    <form action="{{ route('pages.versions.restore', [$page->id, $version->id]) }}" 
                                          method="POST" class="inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" 
                                                class="text-green-600 hover:text-green-900"
                                                onclick="return confirm('Are you sure you want to restore this version?')">
                                            Restore
                                        </button>
                                    </form>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection


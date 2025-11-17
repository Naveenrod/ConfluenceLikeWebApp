@extends('layouts.app')

@section('title', 'Spaces')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Spaces</h1>
        <a href="{{ route('spaces.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Create Space
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($spaces as $space)
            <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold mb-2">
                            <a href="{{ route('spaces.show', $space->id) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-folder mr-2"></i>{{ $space->name }}
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($space->description, 100) }}</p>
                        <div class="flex items-center text-sm text-gray-500">
                            <span><i class="fas fa-user mr-1"></i>{{ $space->owner->name }}</span>
                            <span class="ml-4"><i class="fas fa-file-alt mr-1"></i>{{ $space->pages->count() }} pages</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex space-x-2">
                    <a href="{{ route('spaces.show', $space->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        View <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                    @can('update', $space)
                        <a href="{{ route('spaces.edit', $space->id) }}" class="text-gray-600 hover:text-gray-800 text-sm">
                            Edit
                        </a>
                    @endcan
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12">
                <p class="text-gray-500 mb-4">No spaces yet.</p>
                <a href="{{ route('spaces.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Create Your First Space
                </a>
            </div>
        @endforelse
    </div>

    @if($spaces->hasPages())
        <div class="mt-6">
            {{ $spaces->links() }}
        </div>
    @endif
</div>
@endsection


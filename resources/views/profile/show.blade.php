@extends('layouts.app')

@section('title', $user->name . ' - Profile')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="h-20 w-20 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="ml-6">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    @if($user->bio)
                        <p class="mt-2 text-gray-700">{{ $user->bio }}</p>
                    @endif
                </div>
            </div>
            @if(Auth::id() === $user->id)
                <a href="{{ route('profile.edit') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                    <i class="fas fa-edit mr-2"></i>Edit Profile
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6 text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['pages'] }}</div>
            <div class="text-gray-600 mt-2">Pages</div>
        </div>
        <div class="bg-white shadow rounded-lg p-6 text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['spaces'] }}</div>
            <div class="text-gray-600 mt-2">Spaces</div>
        </div>
        <div class="bg-white shadow rounded-lg p-6 text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['comments'] }}</div>
            <div class="text-gray-600 mt-2">Comments</div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Recent Activity</h2>
        @if($activities->count() > 0)
            <ul class="space-y-4">
                @foreach($activities as $activity)
                    <li class="border-b pb-4 last:border-0">
                        <div class="flex items-start">
                            <div class="flex-1">
                                <p class="text-gray-900">{{ $activity->description }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500">No activity yet.</p>
        @endif
    </div>
</div>
@endsection


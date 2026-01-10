<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Confluence')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        confluence: {
                            bg: '#1d2125',
                            sidebar: '#161a1d',
                            card: '#22272b',
                            border: '#a6c5e229',
                            text: '#b6c2cf',
                            textMuted: '#8c9bab',
                            blue: '#579dff',
                            blueHover: '#85b8ff',
                            purple: '#9f8fef',
                            green: '#4bce97',
                            yellow: '#f5cd47',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #1d2125; color: #b6c2cf; }
        .sidebar-item:hover { background-color: rgba(166, 197, 226, 0.08); }
        .sidebar-item.active { background-color: rgba(87, 157, 255, 0.16); color: #579dff; }
        .scrollbar-thin::-webkit-scrollbar { width: 8px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background-color: rgba(166, 197, 226, 0.2); border-radius: 4px; }
        .dropdown-menu { display: none; }
        .dropdown:hover .dropdown-menu { display: block; }
        input::placeholder { color: #8c9bab; }
        .feed-card { transition: background-color 0.15s ease; }
        .feed-card:hover { background-color: #2c333a; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <!-- Left Sidebar -->
        <aside class="w-64 bg-confluence-sidebar border-r border-confluence-border flex flex-col flex-shrink-0">
            <!-- Logo -->
            <div class="h-14 flex items-center px-4 border-b border-confluence-border">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-confluence-blue rounded flex items-center justify-center">
                        <i class="fas fa-infinity text-white text-sm"></i>
                    </div>
                    <span class="font-semibold text-white text-lg">Confluence</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto scrollbar-thin py-2">
                <!-- Main Navigation -->
                <div class="px-3 mb-4">
                    <a href="{{ route('dashboard') }}" class="sidebar-item flex items-center px-3 py-2 rounded-md text-sm {{ request()->routeIs('dashboard') || request()->routeIs('feed') ? 'active' : '' }}">
                        <i class="fas fa-home w-5 mr-3"></i>
                        For you
                    </a>
                    <div x-data="{ open: false }" class="mt-1">
                        <button @click="open = !open" class="sidebar-item flex items-center justify-between w-full px-3 py-2 rounded-md text-sm">
                            <span class="flex items-center">
                                <i class="fas fa-clock w-5 mr-3"></i>
                                Recent
                            </span>
                            <i class="fas fa-chevron-right text-xs transition-transform" :class="open ? 'rotate-90' : ''"></i>
                        </button>
                        <div x-show="open" x-collapse class="ml-8 mt-1 space-y-1">
                            @php
                                $recentViews = auth()->user()->recentViews()->with('viewable')->limit(5)->get();
                            @endphp
                            @forelse($recentViews as $view)
                                @if($view->viewable)
                                    <a href="{{ $view->viewable instanceof \App\Models\Page ? route('pages.show', $view->viewable) : route('spaces.show', $view->viewable) }}" class="block px-3 py-1.5 text-xs text-confluence-textMuted hover:text-confluence-text truncate">
                                        {{ $view->viewable->title ?? $view->viewable->name }}
                                    </a>
                                @endif
                            @empty
                                <span class="block px-3 py-1.5 text-xs text-confluence-textMuted">No recent items</span>
                            @endforelse
                        </div>
                    </div>
                    <div x-data="{ open: false }" class="mt-1">
                        <button @click="open = !open" class="sidebar-item flex items-center justify-between w-full px-3 py-2 rounded-md text-sm">
                            <span class="flex items-center">
                                <i class="fas fa-star w-5 mr-3"></i>
                                Starred
                            </span>
                            <i class="fas fa-chevron-right text-xs transition-transform" :class="open ? 'rotate-90' : ''"></i>
                        </button>
                        <div x-show="open" x-collapse class="ml-8 mt-1 space-y-1">
                            @php
                                $starredPages = auth()->user()->starredPages()->limit(5)->get();
                            @endphp
                            @forelse($starredPages as $page)
                                <a href="{{ route('pages.show', $page) }}" class="block px-3 py-1.5 text-xs text-confluence-textMuted hover:text-confluence-text truncate">
                                    {{ $page->title }}
                                </a>
                            @empty
                                <span class="block px-3 py-1.5 text-xs text-confluence-textMuted">No starred items</span>
                            @endforelse
                        </div>
                    </div>
                    <div x-data="{ open: true }" class="mt-1">
                        <button @click="open = !open" class="sidebar-item flex items-center justify-between w-full px-3 py-2 rounded-md text-sm">
                            <span class="flex items-center">
                                <i class="fas fa-globe w-5 mr-3"></i>
                                Spaces
                            </span>
                            <i class="fas fa-chevron-right text-xs transition-transform" :class="open ? 'rotate-90' : ''"></i>
                        </button>
                        <div x-show="open" x-collapse class="ml-8 mt-1 space-y-1">
                            @php
                                $userSpaces = \App\Models\Space::where('owner_id', auth()->id())->limit(5)->get();
                            @endphp
                            @forelse($userSpaces as $space)
                                <a href="{{ route('spaces.show', $space) }}" class="block px-3 py-1.5 text-xs text-confluence-textMuted hover:text-confluence-text truncate">
                                    {{ $space->name }}
                                </a>
                            @empty
                                <span class="block px-3 py-1.5 text-xs text-confluence-textMuted">No spaces yet</span>
                            @endforelse
                            <a href="{{ route('spaces.index') }}" class="block px-3 py-1.5 text-xs text-confluence-blue hover:text-confluence-blueHover">
                                View all spaces
                            </a>
                        </div>
                    </div>
                    <a href="#" class="sidebar-item flex items-center px-3 py-2 rounded-md text-sm mt-1">
                        <i class="fas fa-th-large w-5 mr-3"></i>
                        Apps
                    </a>
                </div>

                <!-- Starred Spaces Section -->
                <div class="px-3 mb-4">
                    <div class="flex items-center justify-between px-3 mb-2">
                        <span class="text-xs font-medium text-confluence-textMuted uppercase tracking-wider">Starred spaces</span>
                    </div>
                    @php
                        $starredSpaces = auth()->user()->starredSpaces()->limit(5)->get();
                    @endphp
                    @forelse($starredSpaces as $space)
                        <a href="{{ route('spaces.show', $space) }}" class="sidebar-item flex items-center px-3 py-2 rounded-md text-sm">
                            <span class="w-5 h-5 rounded bg-confluence-purple flex items-center justify-center mr-3 text-xs text-white">
                                {{ strtoupper(substr($space->name, 0, 1)) }}
                            </span>
                            <span class="truncate">{{ $space->name }}</span>
                        </a>
                    @empty
                        <p class="px-3 py-2 text-xs text-confluence-textMuted">Star spaces to see them here</p>
                    @endforelse
                    <a href="{{ route('spaces.index') }}" class="sidebar-item flex items-center px-3 py-2 rounded-md text-sm text-confluence-textMuted">
                        <i class="fas fa-bars w-5 mr-3"></i>
                        View all spaces
                    </a>
                </div>

                <!-- External Links -->
                <div class="px-3 border-t border-confluence-border pt-4">
                    <a href="#" class="sidebar-item flex items-center px-3 py-2 rounded-md text-sm">
                        <i class="fas fa-building w-5 mr-3"></i>
                        Company hub
                        <i class="fas fa-external-link-alt ml-auto text-xs opacity-50"></i>
                    </a>
                    <a href="#" class="sidebar-item flex items-center px-3 py-2 rounded-md text-sm">
                        <i class="fab fa-jira w-5 mr-3 text-confluence-blue"></i>
                        Jira
                        <i class="fas fa-external-link-alt ml-auto text-xs opacity-50"></i>
                    </a>
                    <a href="#" class="sidebar-item flex items-center px-3 py-2 rounded-md text-sm">
                        <i class="fas fa-users w-5 mr-3"></i>
                        Teams
                        <i class="fas fa-external-link-alt ml-auto text-xs opacity-50"></i>
                    </a>
                    <button class="sidebar-item flex items-center px-3 py-2 rounded-md text-sm w-full text-left">
                        <i class="fas fa-ellipsis-h w-5 mr-3"></i>
                        More
                    </button>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="h-14 bg-confluence-sidebar border-b border-confluence-border flex items-center justify-between px-4 flex-shrink-0">
                <div class="flex items-center flex-1">
                    <!-- Search Bar -->
                    <div class="relative max-w-xl flex-1">
                        <form action="{{ route('search') }}" method="GET" class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-confluence-textMuted"></i>
                            <input type="text" name="q" placeholder="Search"
                                class="w-full bg-confluence-card border border-confluence-border rounded-md pl-10 pr-4 py-2 text-sm focus:outline-none focus:border-confluence-blue focus:ring-1 focus:ring-confluence-blue">
                        </form>
                    </div>
                </div>

                <div class="flex items-center space-x-2 ml-4">
                    <!-- Create Button -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="bg-confluence-blue hover:bg-confluence-blueHover text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Create
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-confluence-card border border-confluence-border rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('pages.create') }}" class="block px-4 py-2 text-sm hover:bg-confluence-sidebar">
                                <i class="fas fa-file-alt w-5 mr-2"></i>Page
                            </a>
                            <a href="{{ route('spaces.create') }}" class="block px-4 py-2 text-sm hover:bg-confluence-sidebar">
                                <i class="fas fa-folder w-5 mr-2"></i>Space
                            </a>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <button class="p-2 hover:bg-confluence-card rounded-md relative">
                        <i class="fas fa-bell text-confluence-text"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <!-- Help -->
                    <button class="p-2 hover:bg-confluence-card rounded-md">
                        <i class="fas fa-question-circle text-confluence-text"></i>
                    </button>

                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="w-8 h-8 rounded-full bg-confluence-purple flex items-center justify-center text-white text-sm font-medium">
                            {{ auth()->user()->initials }}
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-64 bg-confluence-card border border-confluence-border rounded-md shadow-lg py-2 z-50">
                            <div class="px-4 py-2 border-b border-confluence-border">
                                <p class="font-medium text-white">{{ auth()->user()->name }}</p>
                                <p class="text-sm text-confluence-textMuted">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm hover:bg-confluence-sidebar">
                                <i class="fas fa-user w-5 mr-2"></i>Profile
                            </a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-confluence-sidebar">
                                <i class="fas fa-cog w-5 mr-2"></i>Settings
                            </a>
                            <div class="border-t border-confluence-border mt-2 pt-2">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm hover:bg-confluence-sidebar">
                                        <i class="fas fa-sign-out-alt w-5 mr-2"></i>Log out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto">
                @if(session('success'))
                    <div class="mx-4 mt-4">
                        <div class="bg-green-900/50 border border-green-700 text-green-300 px-4 py-3 rounded-md relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mx-4 mt-4">
                        <div class="bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 rounded-md relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mx-4 mt-4">
                        <div class="bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 rounded-md relative" role="alert">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Track page views -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pageId = document.body.dataset.pageId;
            const spaceId = document.body.dataset.spaceId;

            if (pageId || spaceId) {
                fetch('{{ route("track.view") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        type: pageId ? 'page' : 'space',
                        id: pageId || spaceId
                    })
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

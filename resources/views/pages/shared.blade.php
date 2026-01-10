<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->title }} - Confluence</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
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
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #1d2125; color: #b6c2cf; }
        .prose-invert { color: #b6c2cf; }
        .prose-invert h1, .prose-invert h2, .prose-invert h3, .prose-invert h4 { color: white; }
        .prose-invert a { color: #579dff; }
        .prose-invert strong { color: white; }
        .prose-invert code { background: #22272b; color: #b6c2cf; padding: 0.2em 0.4em; border-radius: 0.25rem; }
        .prose-invert pre { background: #22272b; }
        .prose-invert blockquote { border-left-color: #579dff; color: #8c9bab; }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <header class="bg-confluence-sidebar border-b border-confluence-border">
        <div class="max-w-4xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-confluence-blue rounded flex items-center justify-center">
                    <i class="fas fa-infinity text-white text-sm"></i>
                </div>
                <span class="font-semibold text-white text-lg">Confluence</span>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-confluence-textMuted">
                    <i class="fas fa-link mr-1"></i>Shared page
                </span>
                <a href="{{ route('login') }}" class="text-sm text-confluence-blue hover:text-confluence-blueHover">
                    Sign in to edit
                </a>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="max-w-4xl mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <i class="fas fa-file-alt text-2xl text-confluence-textMuted mr-3"></i>
                <h1 class="text-3xl font-semibold text-white">{{ $page->title }}</h1>
            </div>
            <div class="flex items-center text-sm text-confluence-textMuted space-x-4">
                <span class="flex items-center">
                    <div class="w-6 h-6 rounded-full bg-confluence-purple flex items-center justify-center text-white text-xs mr-2">
                        {{ strtoupper(substr($page->author->name ?? 'U', 0, 1)) }}
                    </div>
                    {{ $page->author->name ?? 'Unknown' }}
                </span>
                <span>Updated {{ $page->updated_at->format('M j, Y') }}</span>
                @if($page->space)
                    <span class="flex items-center">
                        <i class="fas fa-folder mr-1"></i>{{ $page->space->name }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Page Content -->
        <div class="bg-confluence-card border border-confluence-border rounded-lg p-8">
            <div class="prose prose-invert max-w-none">
                {!! $page->content !!}
            </div>
        </div>

        <!-- Share Info -->
        <div class="mt-8 p-4 bg-confluence-sidebar border border-confluence-border rounded-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center text-sm text-confluence-textMuted">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span>This is a shared view of a Confluence page.</span>
                </div>
                @if($shareLink->permission === 'view')
                    <span class="text-xs bg-confluence-blue/20 text-confluence-blue px-2 py-1 rounded">View only</span>
                @else
                    <span class="text-xs bg-confluence-green/20 text-confluence-green px-2 py-1 rounded">Can edit</span>
                @endif
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-confluence-border mt-12">
        <div class="max-w-4xl mx-auto px-6 py-6 text-center text-sm text-confluence-textMuted">
            <p>Powered by Confluence</p>
        </div>
    </footer>
</body>
</html>

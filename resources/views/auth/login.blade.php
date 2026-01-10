<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Confluence</title>
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
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #1d2125; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center space-x-2 mb-4">
                <div class="w-10 h-10 bg-confluence-blue rounded flex items-center justify-center">
                    <i class="fas fa-infinity text-white"></i>
                </div>
                <span class="text-2xl font-bold text-white">Confluence</span>
            </div>
            <h2 class="text-xl text-white">Log in to continue</h2>
        </div>

        <!-- Login Form -->
        <div class="bg-confluence-card border border-confluence-border rounded-lg p-8">
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-confluence-textMuted mb-2">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           value="{{ old('email') }}"
                           class="w-full bg-confluence-sidebar border border-confluence-border rounded-md px-4 py-3 text-white placeholder-confluence-textMuted focus:outline-none focus:border-confluence-blue focus:ring-1 focus:ring-confluence-blue"
                           placeholder="Enter your email">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-confluence-textMuted mb-2">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="w-full bg-confluence-sidebar border border-confluence-border rounded-md px-4 py-3 text-white placeholder-confluence-textMuted focus:outline-none focus:border-confluence-blue focus:ring-1 focus:ring-confluence-blue"
                           placeholder="Enter your password">
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                               class="h-4 w-4 rounded border-confluence-border bg-confluence-sidebar text-confluence-blue focus:ring-confluence-blue">
                        <label for="remember" class="ml-2 text-sm text-confluence-textMuted">Remember me</label>
                    </div>
                    <a href="#" class="text-sm text-confluence-blue hover:text-confluence-blueHover">Forgot password?</a>
                </div>

                @if($errors->any())
                    <div class="p-3 bg-red-900/30 border border-red-700 rounded-md text-red-300 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <button type="submit"
                        class="w-full bg-confluence-blue hover:bg-confluence-blueHover text-white py-3 px-4 rounded-md font-medium transition">
                    Log in
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-confluence-border text-center">
                <p class="text-sm text-confluence-textMuted">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-confluence-blue hover:text-confluence-blueHover font-medium">
                        Sign up
                    </a>
                </p>
            </div>
        </div>

        <p class="mt-8 text-center text-xs text-confluence-textMuted">
            By logging in, you agree to our Terms of Service and Privacy Policy.
        </p>
    </div>
</body>
</html>

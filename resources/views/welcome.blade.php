<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confluence App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Welcome to Confluence App</h1>
            <p class="text-gray-600 mb-8">A collaboration and documentation platform</p>
            <div class="space-x-4">
                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                    Login
                </a>
                <a href="{{ route('register') }}" class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700">
                    Register
                </a>
            </div>
        </div>
    </div>
</body>
</html>


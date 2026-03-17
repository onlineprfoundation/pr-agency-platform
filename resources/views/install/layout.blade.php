<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Install - Online PR</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .step-active { @apply border-gray-800 text-gray-900 font-medium; }
        .step-done { @apply border-green-500 text-green-600; }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50 min-h-screen">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white border-b py-4">
            <div class="max-w-2xl mx-auto px-4">
                <h1 class="text-xl font-bold text-gray-900">Online PR Installer</h1>
                <p class="text-sm text-gray-500 mt-1">Step-by-step setup wizard</p>
            </div>
        </header>

        <div class="flex-1 py-8">
            <div class="max-w-2xl mx-auto px-4">
                @if($step)
                <div class="flex items-center gap-2 mb-8">
                    <a href="{{ route('install.welcome') }}" class="px-3 py-1 rounded {{ request()->routeIs('install.welcome') ? 'bg-gray-200 font-medium' : 'text-gray-500' }}">1. Requirements</a>
                    <span class="text-gray-300">›</span>
                    <a href="{{ route('install.database') }}" class="px-3 py-1 rounded {{ request()->routeIs('install.database') ? 'bg-gray-200 font-medium' : 'text-gray-500' }}">2. Database</a>
                    <span class="text-gray-300">›</span>
                    <a href="{{ route('install.administrator') }}" class="px-3 py-1 rounded {{ request()->routeIs('install.administrator') ? 'bg-gray-200 font-medium' : 'text-gray-500' }}">3. Admin</a>
                    <span class="text-gray-300">›</span>
                    <span class="px-3 py-1 rounded {{ request()->routeIs('install.complete') ? 'bg-gray-200 font-medium' : 'text-gray-500' }}">4. Done</span>
                </div>
                @endif
                {{ $slot }}
            </div>
        </div>

        <footer class="py-4 text-center text-sm text-gray-500">
            <a href="https://online.pr/opensource/pr-agency-platform" target="_blank" rel="noopener">Powered by Online PR</a>
        </footer>
    </div>
</body>
</html>

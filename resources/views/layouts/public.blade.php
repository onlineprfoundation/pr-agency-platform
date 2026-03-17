<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $metaDescription ?? \App\Models\Setting::get('tagline', config('app.name')) }}">

    <title>{{ $metaTitle ?? \App\Models\Setting::get('site_name', config('app.name')) }}</title>

    @if(\App\Models\Setting::get('favicon_path'))
        <link rel="icon" href="{{ \Illuminate\Support\Facades\Storage::url(\App\Models\Setting::get('favicon_path')) }}">
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white shadow-sm">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center gap-2">
                            @if(\App\Models\Setting::get('logo_path'))
                                <img src="{{ \Illuminate\Support\Facades\Storage::url(\App\Models\Setting::get('logo_path')) }}" alt="{{ \App\Models\Setting::get('site_name', config('app.name')) }}" class="h-8 object-contain">
                            @else
                                <span class="text-xl font-semibold text-gray-800">{{ \App\Models\Setting::get('site_name', config('app.name')) }}</span>
                            @endif
                        </a>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('packages.index') }}" class="text-gray-600 hover:text-gray-900">Packages</a>
                        <a href="{{ route('publications.index') }}" class="text-gray-600 hover:text-gray-900">Publications</a>
                        <a href="{{ route('contact') }}" class="text-gray-600 hover:text-gray-900">Contact</a>
                        <a href="{{ route('quote') }}" class="text-gray-600 hover:text-gray-900">Request Quote</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Register</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </nav>
        </header>

        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="bg-white border-t mt-auto py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-gray-600 text-sm">
                        &copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name', config('app.name')) }}
                        @if (\App\Models\Setting::get('contact_email'))
                            &middot; <a href="mailto:{{ \App\Models\Setting::get('contact_email') }}" class="hover:text-gray-900">{{ \App\Models\Setting::get('contact_email') }}</a>
                        @endif
                    </div>
                    <div class="text-sm text-gray-500">
                        <a href="https://online.pr/opensource/pr-agency-platform" target="_blank" rel="noopener" class="hover:text-gray-700">Powered by Online PR</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>

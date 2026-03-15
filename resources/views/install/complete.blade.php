<x-install-layout>
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <div class="mb-6">
            <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 text-green-600 text-2xl">✓</span>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Installation Complete!</h2>
        <p class="text-gray-600 mt-2">Your Online.PR installation is ready. Log in with your administrator account.</p>
        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 mt-6 bg-gray-800 text-white rounded-md hover:bg-gray-700 font-medium">
            Go to Login →
        </a>
    </div>
</x-install-layout>

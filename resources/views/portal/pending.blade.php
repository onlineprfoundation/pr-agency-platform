<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Client Portal</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 mb-8">
                <p class="text-amber-800">Your account is registered. An administrator will link your account to a client record to give you access to projects.</p>
                <p class="mt-1 text-sm text-amber-700">Until then, you can browse packages, publications, and request a quote below.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <a href="{{ route('packages.index') }}" class="block bg-white rounded-xl border border-gray-200 p-6 hover:border-gray-300 hover:shadow-md transition">
                    <h3 class="font-semibold text-gray-900">Packages</h3>
                    <p class="text-sm text-gray-600 mt-1">Browse our PR packages and buy or request a quote.</p>
                    <span class="inline-flex items-center mt-4 text-gray-800 font-medium">View packages →</span>
                </a>
                <a href="{{ route('publications.index') }}" class="block bg-white rounded-xl border border-gray-200 p-6 hover:border-gray-300 hover:shadow-md transition">
                    <h3 class="font-semibold text-gray-900">Publications</h3>
                    <p class="text-sm text-gray-600 mt-1">See our network of outlets and placements.</p>
                    <span class="inline-flex items-center mt-4 text-gray-800 font-medium">View publications →</span>
                </a>
                <a href="{{ route('quote') }}" class="block bg-white rounded-xl border border-gray-200 p-6 hover:border-gray-300 hover:shadow-md transition">
                    <h3 class="font-semibold text-gray-900">Request Quote</h3>
                    <p class="text-sm text-gray-600 mt-1">Get a custom quote for your PR needs.</p>
                    <span class="inline-flex items-center mt-4 text-gray-800 font-medium">Request quote →</span>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

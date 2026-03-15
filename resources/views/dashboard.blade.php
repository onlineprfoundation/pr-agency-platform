<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('admin.leads.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900">Leads</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-800">{{ \App\Models\Lead::count() }}</p>
                    <p class="mt-1 text-sm text-gray-500">Manage leads from contact and quote forms</p>
                </a>
                <a href="{{ route('admin.packages.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900">Packages</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-800">{{ \App\Models\Package::count() }}</p>
                    <p class="mt-1 text-sm text-gray-500">Manage packages and pricing</p>
                </a>
                <a href="{{ route('admin.clients.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900">Clients</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-800">{{ \App\Models\Client::count() }}</p>
                    <p class="mt-1 text-sm text-gray-500">Manage clients</p>
                </a>
                <a href="{{ route('admin.projects.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900">Projects</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-800">{{ \App\Models\Project::count() }}</p>
                    <p class="mt-1 text-sm text-gray-500">Project management</p>
                </a>
                <a href="{{ route('admin.publications.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900">Publications</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-800">{{ \App\Models\Publication::count() }}</p>
                    <p class="mt-1 text-sm text-gray-500">Manage outlet placements</p>
                </a>
                <a href="{{ route('admin.payments.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900">Payments</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-800">{{ \App\Models\Payment::count() }}</p>
                    <p class="mt-1 text-sm text-gray-500">View payment history</p>
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-4">Welcome to your agency dashboard. Use the navigation above to manage leads, packages, publications, and payments.</p>
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 font-medium">View public site →</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

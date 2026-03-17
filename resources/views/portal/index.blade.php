<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Client Portal</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            {{-- Quick links: Packages, Publications, Quote --}}
            <div class="grid sm:grid-cols-3 gap-4 mb-8">
                <a href="{{ route('packages.index') }}" class="flex items-center gap-3 p-4 bg-white rounded-lg border border-gray-200 hover:border-gray-300 hover:shadow transition">
                    <span class="text-2xl">📦</span>
                    <div>
                        <span class="font-medium text-gray-900">Packages</span>
                        <p class="text-xs text-gray-500">Buy or request quote</p>
                    </div>
                </a>
                <a href="{{ route('publications.index') }}" class="flex items-center gap-3 p-4 bg-white rounded-lg border border-gray-200 hover:border-gray-300 hover:shadow transition">
                    <span class="text-2xl">📰</span>
                    <div>
                        <span class="font-medium text-gray-900">Publications</span>
                        <p class="text-xs text-gray-500">Our outlet network</p>
                    </div>
                </a>
                <a href="{{ route('quote') }}" class="flex items-center gap-3 p-4 bg-white rounded-lg border border-gray-200 hover:border-gray-300 hover:shadow transition">
                    <span class="text-2xl">✉️</span>
                    <div>
                        <span class="font-medium text-gray-900">Request Quote</span>
                        <p class="text-xs text-gray-500">Custom PR quote</p>
                    </div>
                </a>
            </div>

            {{-- My Projects --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-900">My Projects</h3>
                    <p class="text-sm text-gray-500 mt-1">Your active projects and deliverables</p>
                </div>
                <div class="p-6">
                    @if($projects->isEmpty())
                        <p class="text-gray-500 text-center py-8">You have no projects yet. Browse <a href="{{ route('packages.index') }}" class="text-gray-800 hover:underline">packages</a> or <a href="{{ route('quote') }}" class="text-gray-800 hover:underline">request a quote</a> to get started.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($projects as $project)
                                <a href="{{ route('portal.project', $project) }}" class="block p-4 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $project->name }}</h4>
                                            <p class="text-sm text-gray-500 mt-1">{{ ucfirst($project->status) }}</p>
                                            @if($project->publications->isNotEmpty())
                                                <p class="text-sm text-gray-600 mt-2">{{ $project->publications->pluck('name')->join(', ') }}</p>
                                            @endif
                                        </div>
                                        <span class="text-gray-400">→</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

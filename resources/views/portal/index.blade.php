<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Client Portal – My Projects</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            @if($projects->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-center">
                    <p class="text-gray-500">You have no projects yet.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($projects as $project)
                        <a href="{{ route('portal.project', $project) }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $project->name }}</h3>
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
</x-app-layout>

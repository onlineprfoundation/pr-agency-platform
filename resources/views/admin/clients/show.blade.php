<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Client: {{ $client->name }}</h2>
            <a href="{{ route('admin.projects.create', ['client_id' => $client->id]) }}" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">New Project</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif
            @if(session('info'))
                <div class="mb-4 p-4 bg-blue-50 text-blue-800 rounded-md">{{ session('info') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><dt class="text-sm text-gray-500">Email</dt><dd><a href="mailto:{{ $client->email }}" class="text-blue-600 hover:underline">{{ $client->email }}</a></dd></div>
                        <div><dt class="text-sm text-gray-500">Company</dt><dd>{{ $client->company ?? '-' }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Phone</dt><dd>{{ $client->phone ?? '-' }}</dd></div>
                    </dl>
                    <div class="mt-6 flex gap-4">
                        <a href="{{ route('admin.clients.edit', $client) }}" class="text-gray-600 hover:text-gray-900">Edit</a>
                        @if($client->user)
                            <span class="text-green-600 text-sm">Portal access enabled</span>
                        @else
                            <form action="{{ route('admin.clients.invite', $client) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-blue-600 hover:underline">Invite to portal</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Projects ({{ $client->projects->count() }})</h3>
                    @if($client->projects->isEmpty())
                        <p class="text-gray-500">No projects yet.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach($client->projects as $project)
                                <li>
                                    <a href="{{ route('admin.projects.show', $project) }}" class="text-blue-600 hover:underline">{{ $project->name }}</a>
                                    <span class="text-gray-500 text-sm">({{ $project->status }})</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

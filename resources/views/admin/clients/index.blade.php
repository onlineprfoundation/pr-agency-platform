<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Clients</h2>
            <a href="{{ route('admin.clients.create') }}" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Add Client</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Projects</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($clients as $client)
                                <tr>
                                    <td class="px-4 py-2 font-medium">{{ $client->name }}</td>
                                    <td class="px-4 py-2">{{ $client->email }}</td>
                                    <td class="px-4 py-2">{{ $client->company ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $client->projects_count }}</td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('admin.clients.edit', $client) }}" class="text-gray-600 hover:text-gray-900 mr-4">Edit</a>
                                        <a href="{{ route('admin.clients.show', $client) }}" class="text-gray-600 hover:text-gray-900 mr-4">View</a>
                                        <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" class="inline" onsubmit="return confirm('Delete this client?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">No clients yet. <a href="{{ route('admin.clients.create') }}" class="text-gray-800 hover:underline">Add one</a></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">{{ $clients->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>

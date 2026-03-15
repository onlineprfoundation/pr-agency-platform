<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Projects</h2>
            <a href="{{ route('admin.projects.create') }}" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">New Project</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            <form method="GET" class="mb-4">
                <select name="status" class="rounded-md border-gray-300" onchange="this.form.submit()">
                    <option value="">All statuses</option>
                    @foreach(\App\Models\Project::STATUSES as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </form>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Project</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Value</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Due</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($projects as $project)
                                <tr>
                                    <td class="px-4 py-2 font-medium">{{ $project->name }}</td>
                                    <td class="px-4 py-2">{{ $project->client->name }}</td>
                                    <td class="px-4 py-2">{{ ucfirst($project->status) }}</td>
                                    <td class="px-4 py-2">{{ $project->formatted_value ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $project->due_date?->format('M j, Y') ?? '-' }}</td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('admin.projects.show', $project) }}" class="text-gray-600 hover:text-gray-900">View</a>
                                        <a href="{{ route('admin.projects.edit', $project) }}" class="text-gray-600 hover:text-gray-900 ml-4">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">No projects yet. <a href="{{ route('admin.projects.create') }}" class="text-gray-800 hover:underline">Create one</a></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">{{ $projects->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>

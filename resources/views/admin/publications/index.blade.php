<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Publications
            </h2>
            <a href="{{ route('admin.publications.create') }}" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Add Publication</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Publication</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Words</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Backlinks</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">TAT</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Indexed</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">DoFollow</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Genre</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Region</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">DA</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Traffic</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Check</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Last Modified</th>
                                <th class="px-3 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($publications as $publication)
                                <tr>
                                    <td class="px-3 py-2 font-medium">
                                        <a href="{{ route('admin.publications.edit', $publication) }}" class="text-gray-900 hover:underline">{{ $publication->name }}</a>
                                        @if($publication->link)
                                            <a href="{{ $publication->link }}" target="_blank" rel="noopener" class="text-gray-400 hover:text-gray-600 ml-1" title="Open URL">↗</a>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">{{ $publication->formatted_price ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $publication->words_allowed ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $publication->backlinks_count ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $publication->tat ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $publication->indexed ? 'Yes' : '-' }}</td>
                                    <td class="px-3 py-2">{{ $publication->dofollow ? 'Yes' : '-' }}</td>
                                    <td class="px-3 py-2">{{ $publication->genre ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $publication->region ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $publication->da ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $publication->traffic ? number_format($publication->traffic) : '-' }}</td>
                                    <td class="px-3 py-2">
                                        @if($publication->check_da_url)
                                            <a href="{{ $publication->check_da_url }}" target="_blank" rel="noopener" class="text-blue-600 hover:underline text-xs">DA</a>
                                            @if($publication->check_traffic_url)
                                                <span class="text-gray-300">|</span>
                                            @endif
                                        @endif
                                        @if($publication->check_traffic_url)
                                            <a href="{{ $publication->check_traffic_url }}" target="_blank" rel="noopener" class="text-blue-600 hover:underline text-xs">Traffic</a>
                                        @endif
                                        @if(!$publication->check_da_url && !$publication->check_traffic_url)
                                            -
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">{{ $publication->last_modified_at?->format('M j, Y') ?? '-' }}</td>
                                    <td class="px-3 py-2">
                                        <a href="{{ route('admin.publications.edit', $publication) }}" class="text-gray-600 hover:text-gray-900 mr-2">Edit</a>
                                        <form method="POST" action="{{ route('admin.publications.destroy', $publication) }}" class="inline" onsubmit="return confirm('Delete this publication?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="px-4 py-8 text-center text-gray-500">No publications yet. <a href="{{ route('admin.publications.create') }}" class="text-gray-800 hover:underline">Add one</a></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">{{ $publications->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>

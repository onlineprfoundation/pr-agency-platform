<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pages</h2>
            <a href="{{ route('admin.pages.create') }}" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">New Page</a>
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
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($pages as $page)
                                <tr>
                                    <td class="px-4 py-2 font-medium">{{ $page->title }}</td>
                                    <td class="px-4 py-2 text-gray-500">{{ $page->slug }}</td>
                                    <td class="px-4 py-2">
                                        @if($page->is_published)
                                            <span class="text-green-600">Published</span>
                                        @else
                                            <span class="text-gray-500">Draft</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ $page->sort_order }}</td>
                                    <td class="px-4 py-2">
                                        @if($page->is_published)
                                            <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="text-gray-600 hover:text-gray-900 mr-4">View</a>
                                        @endif
                                        <a href="{{ route('admin.pages.edit', $page) }}" class="text-gray-600 hover:text-gray-900 mr-4">Edit</a>
                                        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" class="inline" onsubmit="return confirm('Delete this page?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">No pages yet. <a href="{{ route('admin.pages.create') }}" class="text-gray-800 hover:underline">Create one</a></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">{{ $pages->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>

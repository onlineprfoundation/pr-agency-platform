<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Packages
            </h2>
            <a href="{{ route('admin.packages.create') }}" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Add Package</a>
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
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Active</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($packages as $package)
                                <tr>
                                    <td class="px-4 py-2 font-medium">{{ $package->name }}</td>
                                    <td class="px-4 py-2">{{ $package->formatted_price }}</td>
                                    <td class="px-4 py-2">{{ $package->is_active ? 'Yes' : 'No' }}</td>
                                    <td class="px-4 py-2">{{ $package->sort_order }}</td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('admin.packages.edit', $package) }}" class="text-gray-600 hover:text-gray-900 mr-4">Edit</a>
                                        <form method="POST" action="{{ route('admin.packages.destroy', $package) }}" class="inline" onsubmit="return confirm('Delete this package?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">No packages yet. <a href="{{ route('admin.packages.create') }}" class="text-gray-800 hover:underline">Add one</a></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">{{ $packages->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>

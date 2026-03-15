<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Package: {{ $package->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-4">
                        <div><dt class="text-sm text-gray-500">Name</dt><dd>{{ $package->name }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Slug</dt><dd>{{ $package->slug }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Price</dt><dd>{{ $package->formatted_price }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Active</dt><dd>{{ $package->is_active ? 'Yes' : 'No' }}</dd></div>
                        @if($package->description)
                            <div class="col-span-full"><dt class="text-sm text-gray-500">Description</dt><dd class="mt-1">{{ nl2br(e($package->description)) }}</dd></div>
                        @endif
                    </dl>
                    <div class="mt-6">
                        <a href="{{ route('admin.packages.edit', $package) }}" class="text-gray-600 hover:text-gray-900">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

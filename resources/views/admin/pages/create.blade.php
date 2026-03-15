<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Page</h2>
            <a href="{{ route('admin.pages.index') }}" class="text-gray-600 hover:text-gray-900">Back to pages</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.pages.store') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">
                @csrf
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required class="mt-1 block w-full rounded-md border-gray-300">
                    @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700">Slug (optional, auto-generated from title)</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="about-us" class="mt-1 block w-full rounded-md border-gray-300">
                    @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Lowercase letters, numbers, hyphens only. URL: /p/your-slug</p>
                </div>
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea name="content" id="content" rows="12" class="mt-1 block w-full rounded-md border-gray-300">{{ old('content') }}</textarea>
                    @error('content')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta description</label>
                    <textarea name="meta_description" id="meta_description" rows="2" class="mt-1 block w-full rounded-md border-gray-300">{{ old('meta_description') }}</textarea>
                </div>
                <div>
                    <label class="flex items-center gap-2">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-gray-700">Published</span>
                    </label>
                </div>
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300">
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Create Page</button>
            </form>
        </div>
    </div>
</x-app-layout>

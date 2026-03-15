<div>
    <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
    <input type="text" name="name" id="name" value="{{ old('name', $package->name) }}" required
        class="mt-1 block w-full rounded-md border-gray-300 @error('name') border-red-500 @enderror">
    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="slug" class="block text-sm font-medium text-gray-700">Slug (optional, auto-generated from name)</label>
    <input type="text" name="slug" id="slug" value="{{ old('slug', $package->slug) }}"
        class="mt-1 block w-full rounded-md border-gray-300 @error('slug') border-red-500 @enderror">
    @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
    <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 @error('description') border-red-500 @enderror">{{ old('description', $package->description) }}</textarea>
    @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="price_cents" class="block text-sm font-medium text-gray-700">Price (cents, leave empty for "Quote")</label>
    <input type="number" name="price_cents" id="price_cents" value="{{ old('price_cents', $package->price_cents) }}" min="0" step="1"
        class="mt-1 block w-full rounded-md border-gray-300 @error('price_cents') border-red-500 @enderror" placeholder="e.g. 9999 for $99.99">
    @error('price_cents')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="image_path" class="block text-sm font-medium text-gray-700">Image path (storage path)</label>
    <input type="text" name="image_path" id="image_path" value="{{ old('image_path', $package->image_path) }}"
        class="mt-1 block w-full rounded-md border-gray-300">
</div>
<div>
    <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort order</label>
    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $package->sort_order ?? 0) }}" min="0"
        class="mt-1 block w-full rounded-md border-gray-300">
</div>
<div>
    <label class="flex items-center">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $package->is_active ?? true) ? 'checked' : '' }}
            class="rounded border-gray-300">
        <span class="ml-2 text-sm text-gray-700">Active (visible on public site)</span>
    </label>
</div>

<div>
    <label for="name" class="block text-sm font-medium text-gray-700">Publication name *</label>
    <input type="text" name="name" id="name" value="{{ old('name', $publication->name) }}" required
        class="mt-1 block w-full rounded-md border-gray-300 @error('name') border-red-500 @enderror">
    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="link" class="block text-sm font-medium text-gray-700">Publication URL (used for Check DA / Check Traffic)</label>
    <input type="url" name="link" id="link" value="{{ old('link', $publication->link) }}"
        class="mt-1 block w-full rounded-md border-gray-300 @error('link') border-red-500 @enderror" placeholder="https://example.com">
    @error('link')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    <p class="mt-1 text-xs text-gray-500">Used as input for Ahrefs DA and Traffic checker links</p>
</div>
<div>
    <label for="logo_path" class="block text-sm font-medium text-gray-700">Logo path (storage path)</label>
    <input type="text" name="logo_path" id="logo_path" value="{{ old('logo_path', $publication->logo_path) }}"
        class="mt-1 block w-full rounded-md border-gray-300">
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="price_usd" class="block text-sm font-medium text-gray-700">Price ($ USD)</label>
        <input type="number" name="price_usd" id="price_usd" value="{{ old('price_usd', $publication->price_usd) }}" min="0" step="0.01"
            class="mt-1 block w-full rounded-md border-gray-300 @error('price_usd') border-red-500 @enderror">
        @error('price_usd')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="words_allowed" class="block text-sm font-medium text-gray-700">Words Allowed</label>
        <input type="text" name="words_allowed" id="words_allowed" value="{{ old('words_allowed', $publication->words_allowed) }}"
            class="mt-1 block w-full rounded-md border-gray-300" placeholder="e.g. 500 or 500-800">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="backlinks_count" class="block text-sm font-medium text-gray-700">No. of Backlinks</label>
        <input type="number" name="backlinks_count" id="backlinks_count" value="{{ old('backlinks_count', $publication->backlinks_count) }}" min="0"
            class="mt-1 block w-full rounded-md border-gray-300">
    </div>
    <div>
        <label for="tat" class="block text-sm font-medium text-gray-700">TAT (Turnaround Time)</label>
        <input type="text" name="tat" id="tat" value="{{ old('tat', $publication->tat) }}"
            class="mt-1 block w-full rounded-md border-gray-300" placeholder="e.g. 3-5 days">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="flex items-center">
            <input type="checkbox" name="indexed" value="1" {{ old('indexed', $publication->indexed ?? false) ? 'checked' : '' }}
                class="rounded border-gray-300">
            <span class="ml-2 text-sm text-gray-700">Indexed</span>
        </label>
    </div>
    <div>
        <label class="flex items-center">
            <input type="checkbox" name="dofollow" value="1" {{ old('dofollow', $publication->dofollow ?? false) ? 'checked' : '' }}
                class="rounded border-gray-300">
            <span class="ml-2 text-sm text-gray-700">DoFollow</span>
        </label>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="genre" class="block text-sm font-medium text-gray-700">Genre</label>
        <input type="text" name="genre" id="genre" value="{{ old('genre', $publication->genre) }}"
            class="mt-1 block w-full rounded-md border-gray-300">
    </div>
    <div>
        <label for="region" class="block text-sm font-medium text-gray-700">Region</label>
        <input type="text" name="region" id="region" value="{{ old('region', $publication->region) }}"
            class="mt-1 block w-full rounded-md border-gray-300">
    </div>
</div>

<div>
    <label for="disclaimer" class="block text-sm font-medium text-gray-700">Disclaimer</label>
    <textarea name="disclaimer" id="disclaimer" rows="3" class="mt-1 block w-full rounded-md border-gray-300">{{ old('disclaimer', $publication->disclaimer) }}</textarea>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="da" class="block text-sm font-medium text-gray-700">DA (Domain Authority)</label>
        <input type="number" name="da" id="da" value="{{ old('da', $publication->da) }}" min="0" max="100"
            class="mt-1 block w-full rounded-md border-gray-300" placeholder="0-100">
        <p class="mt-1 text-xs text-gray-500">Check via <a href="https://ahrefs.com/website-authority-checker/" target="_blank" rel="noopener" class="text-blue-600 hover:underline">Ahrefs</a></p>
    </div>
    <div>
        <label for="traffic" class="block text-sm font-medium text-gray-700">Traffic</label>
        <input type="number" name="traffic" id="traffic" value="{{ old('traffic', $publication->traffic) }}" min="0"
            class="mt-1 block w-full rounded-md border-gray-300">
        <p class="mt-1 text-xs text-gray-500">Check via <a href="https://ahrefs.com/traffic-checker/" target="_blank" rel="noopener" class="text-blue-600 hover:underline">Ahrefs</a></p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="last_modified_at" class="block text-sm font-medium text-gray-700">Last Modified Date</label>
        <input type="date" name="last_modified_at" id="last_modified_at" value="{{ old('last_modified_at', $publication->last_modified_at?->format('Y-m-d')) }}"
            class="mt-1 block w-full rounded-md border-gray-300">
    </div>
    <div>
        <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort order</label>
        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $publication->sort_order ?? 0) }}" min="0"
            class="mt-1 block w-full rounded-md border-gray-300">
    </div>
</div>

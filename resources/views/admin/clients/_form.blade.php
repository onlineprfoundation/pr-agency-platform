<div>
    <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
    <input type="text" name="name" id="name" value="{{ old('name', $client->name) }}" required class="mt-1 block w-full rounded-md border-gray-300">
    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
    <input type="email" name="email" id="email" value="{{ old('email', $client->email) }}" required class="mt-1 block w-full rounded-md border-gray-300">
    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="company" class="block text-sm font-medium text-gray-700">Company</label>
    <input type="text" name="company" id="company" value="{{ old('company', $client->company) }}" class="mt-1 block w-full rounded-md border-gray-300">
</div>
<div>
    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
    <input type="text" name="phone" id="phone" value="{{ old('phone', $client->phone) }}" class="mt-1 block w-full rounded-md border-gray-300">
</div>
<div>
    <label for="logo_path" class="block text-sm font-medium text-gray-700">Logo path</label>
    <input type="text" name="logo_path" id="logo_path" value="{{ old('logo_path', $client->logo_path) }}" class="mt-1 block w-full rounded-md border-gray-300">
</div>
<div>
    <label for="link" class="block text-sm font-medium text-gray-700">Link</label>
    <input type="url" name="link" id="link" value="{{ old('link', $client->link) }}" class="mt-1 block w-full rounded-md border-gray-300">
</div>

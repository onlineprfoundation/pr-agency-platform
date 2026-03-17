@php use Illuminate\Support\Facades\Storage; @endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Order: {{ $order->package->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 text-red-800 rounded-md">{{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">
                <div>
                    <p class="text-sm text-gray-500">Status: <span class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span></p>
                    @if($order->live_link)
                        <p class="mt-2">Live link: <a href="{{ $order->live_link }}" target="_blank" rel="noopener" class="text-blue-600 hover:underline">{{ $order->live_link }}</a></p>
                    @endif
                </div>

                @if($order->canSubmit())
                <form method="POST" action="{{ route('portal.orders.submit', $order) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $order->title) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300">
                        @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700">Content *</label>
                        <textarea name="content" id="content" rows="8" required
                            class="mt-1 block w-full rounded-md border-gray-300">{{ old('content', $order->content) }}</textarea>
                        @error('content')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="featured_image" class="block text-sm font-medium text-gray-700">Featured image</label>
                        <input type="file" name="featured_image" id="featured_image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500">
                        @error('featured_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="documents" class="block text-sm font-medium text-gray-700">Documents (optional)</label>
                        <input type="file" name="documents[]" id="documents" multiple class="mt-1 block w-full text-sm text-gray-500">
                        @error('documents.*')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="px-6 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 font-medium">Submit</button>
                </form>
                @else
                <div class="space-y-4">
                    @if($order->title)<p><strong>Title:</strong> {{ $order->title }}</p>@endif
                    @if($order->content)<div><strong>Content:</strong><div class="mt-1 prose max-w-none">{{ nl2br(e($order->content)) }}</div></div>@endif
                    @if($order->featured_image_path)
                        <div><strong>Featured image:</strong><br><img src="{{ Storage::url($order->featured_image_path) }}" alt="Featured" class="mt-2 max-w-md rounded"></div>
                    @endif
                    @if($order->documents->isNotEmpty())
                        <div><strong>Documents:</strong>
                            <ul class="mt-2 space-y-1">
                                @foreach($order->documents as $doc)
                                    <li><a href="{{ route('portal.orders.documents.download', [$order, $doc]) }}" class="text-blue-600 hover:underline">{{ $doc->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

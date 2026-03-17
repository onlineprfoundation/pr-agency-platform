@php use Illuminate\Support\Facades\Storage; @endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Order #{{ $order->id }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">
                <div class="grid sm:grid-cols-2 gap-4">
                    <p><strong>Package:</strong> {{ $order->package->name }}</p>
                    <p><strong>Email:</strong> {{ $order->email }}</p>
                    <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                    <p><strong>Date:</strong> {{ $order->created_at->format('M j, Y H:i') }}</p>
                </div>

                @if($order->title)<p><strong>Title:</strong> {{ $order->title }}</p>@endif
                @if($order->content)<div><strong>Content:</strong><div class="mt-1 prose max-w-none">{{ nl2br(e($order->content)) }}</div></div>@endif
                @if($order->featured_image_path)
                    <div><strong>Featured image:</strong><br><img src="{{ Storage::url($order->featured_image_path) }}" alt="Featured" class="mt-2 max-w-md rounded"></div>
                @endif
                @if($order->documents->isNotEmpty())
                    <div><strong>Documents:</strong>
                        <ul class="mt-2 space-y-1">
                            @foreach($order->documents as $doc)
                                <li>{{ $doc->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="border-t pt-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300">
                            @foreach(['pending_submission', 'submitted', 'in_progress', 'completed'] as $s)
                                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="live_link" class="block text-sm font-medium text-gray-700">Live link</label>
                        <input type="url" name="live_link" id="live_link" value="{{ old('live_link', $order->live_link) }}"
                            placeholder="https://..."
                            class="mt-1 block w-full rounded-md border-gray-300">
                        @error('live_link')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="px-6 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 font-medium">Update (sends email to customer)</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

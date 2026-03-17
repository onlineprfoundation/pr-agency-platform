<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Orders</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($orders->isEmpty())
                        <p class="text-gray-500 text-center py-12">You have no orders yet. <a href="{{ route('packages.index') }}" class="text-gray-800 hover:underline font-medium">Browse packages</a> to get started.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($orders as $order)
                                <a href="{{ route('portal.orders.show', $order) }}" class="block p-4 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $order->package->name }}</h4>
                                            <p class="text-sm text-gray-500 mt-1">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                                            <p class="text-sm text-gray-500">{{ $order->created_at->format('M j, Y') }}</p>
                                            @if($order->live_link)
                                                <p class="text-sm text-blue-600 mt-2">Live: <a href="{{ $order->live_link }}" target="_blank" rel="noopener" class="hover:underline" onclick="event.stopPropagation()">{{ Str::limit($order->live_link, 50) }}</a></p>
                                            @endif
                                        </div>
                                        <span class="text-gray-400">→</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

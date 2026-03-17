<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Client Portal</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            {{-- Quick links: Packages, Publications, Quote --}}
            <div class="grid sm:grid-cols-3 gap-4 mb-8">
                <a href="{{ route('packages.index') }}" class="flex items-center gap-3 p-4 bg-white rounded-lg border border-gray-200 hover:border-gray-300 hover:shadow transition">
                    <span class="text-2xl">📦</span>
                    <div>
                        <span class="font-medium text-gray-900">Packages</span>
                        <p class="text-xs text-gray-500">Buy or request quote</p>
                    </div>
                </a>
                <a href="{{ route('publications.index') }}" class="flex items-center gap-3 p-4 bg-white rounded-lg border border-gray-200 hover:border-gray-300 hover:shadow transition">
                    <span class="text-2xl">📰</span>
                    <div>
                        <span class="font-medium text-gray-900">Publications</span>
                        <p class="text-xs text-gray-500">Our outlet network</p>
                    </div>
                </a>
                <a href="{{ route('quote') }}" class="flex items-center gap-3 p-4 bg-white rounded-lg border border-gray-200 hover:border-gray-300 hover:shadow transition">
                    <span class="text-2xl">✉️</span>
                    <div>
                        <span class="font-medium text-gray-900">Request Quote</span>
                        <p class="text-xs text-gray-500">Custom PR quote</p>
                    </div>
                </a>
            </div>

            {{-- My Orders (package + publication) --}}
            @if($orders->isNotEmpty() || $publicationOrders->isNotEmpty())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-900">My Orders</h3>
                    <p class="text-sm text-gray-500 mt-1">Your package purchases – submit content or track status</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($orders as $order)
                            <a href="{{ route('portal.orders.show', $order) }}" class="block p-4 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="text-xs text-gray-500">Package</span>
                                        <h4 class="font-medium text-gray-900">{{ $order->package->name }}</h4>
                                        <p class="text-sm text-gray-500 mt-1">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                                        @if($order->live_link)
                                            <p class="text-sm text-blue-600 mt-2">Live: <a href="{{ $order->live_link }}" target="_blank" rel="noopener" class="hover:underline">{{ Str::limit($order->live_link, 50) }}</a></p>
                                        @endif
                                    </div>
                                    <span class="text-gray-400">→</span>
                                </div>
                            </a>
                        @endforeach
                        @foreach($publicationOrders as $order)
                            <a href="{{ route('portal.publication-orders.show', $order) }}" class="block p-4 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="text-xs text-gray-500">Publication</span>
                                        <h4 class="font-medium text-gray-900">{{ $order->publication->name }}</h4>
                                        <p class="text-sm text-gray-500 mt-1">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                                        @if($order->live_link)
                                            <p class="text-sm text-blue-600 mt-2">Live: <a href="{{ $order->live_link }}" target="_blank" rel="noopener" class="hover:underline">{{ Str::limit($order->live_link, 50) }}</a></p>
                                        @endif
                                    </div>
                                    <span class="text-gray-400">→</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    @if($orders->isNotEmpty() || $publicationOrders->isNotEmpty())
                    <div class="mt-4 flex gap-4">
                        @if($orders->isNotEmpty())<a href="{{ route('portal.orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Package orders →</a>@endif
                        @if($publicationOrders->isNotEmpty())<a href="{{ route('portal.publication-orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Publication orders →</a>@endif
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- My Projects --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-900">My Projects</h3>
                    <p class="text-sm text-gray-500 mt-1">Your active projects and deliverables</p>
                </div>
                <div class="p-6">
                    @if($projects->isEmpty())
                        <p class="text-gray-500 text-center py-8">You have no projects yet. Browse <a href="{{ route('packages.index') }}" class="text-gray-800 hover:underline">packages</a> or <a href="{{ route('quote') }}" class="text-gray-800 hover:underline">request a quote</a> to get started.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($projects as $project)
                                <a href="{{ route('portal.project', $project) }}" class="block p-4 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $project->name }}</h4>
                                            <p class="text-sm text-gray-500 mt-1">{{ ucfirst($project->status) }}</p>
                                            @if($project->publications->isNotEmpty())
                                                <p class="text-sm text-gray-600 mt-2">{{ $project->publications->pluck('name')->join(', ') }}</p>
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

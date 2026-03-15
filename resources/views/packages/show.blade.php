<x-public-layout>
    <div class="py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('packages.index') }}" class="text-gray-600 hover:text-gray-900 mb-6 inline-block">← Back to packages</a>

            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                @if($package->image_path)
                    <img src="{{ asset('storage/' . $package->image_path) }}" alt="{{ $package->name }}" class="w-full h-64 object-cover">
                @endif
                <div class="p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $package->name }}</h1>
                    <p class="text-2xl font-bold text-gray-900 mb-6">{{ $package->formatted_price }}</p>
                    <div class="prose text-gray-600 mb-8">
                        {!! nl2br(e($package->description)) !!}
                    </div>
                    @if($package->hasPrice())
                        <a href="{{ route('checkout.create', $package) }}" class="inline-flex items-center px-6 py-3 bg-gray-800 text-white rounded-md hover:bg-gray-700 font-medium">
                            Buy now
                        </a>
                    @endif
                    <a href="{{ route('quote') }}?package={{ urlencode($package->name) }}" class="inline-flex items-center px-6 py-3 ml-4 border border-gray-300 rounded-md hover:bg-gray-50 font-medium">
                        Request quote
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>

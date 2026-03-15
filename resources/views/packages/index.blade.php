<x-public-layout>
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-3xl font-bold text-gray-900">Our Packages</h1>
                <p class="text-gray-600 mt-2">Choose the right package for your PR needs</p>
            </div>

            @if($packages->isEmpty())
                <p class="text-center text-gray-600">No packages available at the moment. <a href="{{ route('contact') }}" class="text-gray-800 hover:underline">Contact us</a> for a custom quote.</p>
            @else
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($packages as $package)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                            @if($package->image_path)
                                <img src="{{ asset('storage/' . $package->image_path) }}" alt="{{ $package->name }}" class="w-full h-48 object-cover">
                            @endif
                            <div class="p-6">
                                <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $package->name }}</h2>
                                <p class="text-gray-600 mb-4">{{ $package->description }}</p>
                                <p class="text-2xl font-bold text-gray-900 mb-4">{{ $package->formatted_price }}</p>
                                @if($package->hasPrice())
                                    <a href="{{ route('checkout.create', $package) }}" class="block w-full text-center px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 font-medium mb-2">
                                        Buy now
                                    </a>
                                @endif
                                <a href="{{ route('quote') }}?package={{ $package->name }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 font-medium">
                                    Request quote
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-public-layout>

<x-public-layout>
    <div class="py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Page Header --}}
            <div class="text-center mb-16">
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 tracking-tight">Our Packages</h1>
                <p class="text-xl text-gray-600 mt-4 max-w-2xl mx-auto">Choose the right package for your PR needs. Transparent pricing, quality placements.</p>
            </div>

            @if($packages->isEmpty())
                <div class="max-w-xl mx-auto text-center py-16 px-6 rounded-2xl bg-gray-50 border border-gray-200">
                    <p class="text-gray-600 text-lg mb-6">No packages available at the moment.</p>
                    <p class="text-gray-500 mb-8">We'd love to create a custom solution for you.</p>
                    <a href="{{ route('contact') }}" class="inline-flex items-center px-6 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-700 font-medium">
                        Contact us for a quote
                    </a>
                </div>
            @else
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($packages as $index => $package)
                        @php $isFeatured = $index === 0; @endphp
                        <div class="group relative flex flex-col rounded-2xl overflow-hidden border-2 {{ $isFeatured ? 'border-gray-800 shadow-xl ring-2 ring-gray-800/10' : 'border-gray-200 shadow-md hover:shadow-xl hover:border-gray-300' }} bg-white transition-all duration-200">
                            @if($isFeatured)
                                <div class="absolute top-4 right-4 z-10 px-3 py-1 bg-gray-800 text-white text-xs font-semibold rounded-full uppercase tracking-wide">
                                    Popular
                                </div>
                            @endif
                            @if($package->image_path)
                                <div class="aspect-[16/10] overflow-hidden bg-gray-100">
                                    <img src="{{ asset('storage/' . $package->image_path) }}" alt="{{ $package->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                            @else
                                <div class="aspect-[16/10] bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <span class="text-4xl font-bold text-gray-300">{{ substr($package->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="flex flex-col flex-1 p-6 sm:p-8">
                                <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $package->name }}</h2>
                                <p class="text-gray-600 flex-1 mb-6">{{ $package->description }}</p>
                                <p class="text-2xl font-bold text-gray-900 mb-6">{{ $package->formatted_price }}</p>
                                <div class="space-y-3">
                                    @if($package->hasPrice())
                                        <a href="{{ route('checkout.create', $package) }}" class="block w-full text-center px-4 py-3.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700 font-medium transition shadow-lg shadow-gray-800/20">
                                            Buy now
                                        </a>
                                    @endif
                                    <a href="{{ route('quote') }}?package={{ urlencode($package->name) }}" class="block w-full text-center px-4 py-3.5 border-2 border-gray-300 rounded-lg hover:border-gray-400 hover:bg-gray-50 font-medium transition">
                                        Request quote
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-public-layout>

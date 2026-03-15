<x-public-layout>
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    {{ \App\Models\Setting::get('home_hero_title') ?: \App\Models\Setting::get('site_name', config('app.name')) }}
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    {{ \App\Models\Setting::get('home_hero_subtitle') ?: \App\Models\Setting::get('tagline', 'Professional PR services for your brand.') }}
                </p>
                <div class="mt-8 flex gap-4 justify-center">
                    <a href="{{ route('packages.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-800 text-white rounded-md hover:bg-gray-700 font-medium">
                        {{ \App\Models\Setting::get('home_hero_cta_text') ?: 'View Packages' }}
                    </a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-md hover:bg-gray-50 font-medium">
                        Get in Touch
                    </a>
                </div>
            </div>

            @if(\App\Models\Setting::get('home_show_packages', '1') === '1' && $packages->isNotEmpty())
                <section class="mb-16">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Our Packages</h2>
                    <div class="grid md:grid-cols-3 gap-8">
                        @foreach($packages as $package)
                            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $package->name }}</h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ Str::limit($package->description, 120) }}</p>
                                <p class="text-xl font-bold text-gray-900 mb-4">{{ $package->formatted_price }}</p>
                                <a href="{{ route('packages.show', $package) }}" class="text-gray-800 font-medium hover:underline">Learn more →</a>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-8">
                        <a href="{{ route('packages.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">View all packages</a>
                    </div>
                </section>
            @endif

            @if(\App\Models\Setting::get('home_show_publications', '1') === '1' && $publications->isNotEmpty())
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">We Work With</h2>
                    <div class="flex flex-wrap justify-center gap-8 items-center">
                        @foreach($publications as $publication)
                            @if($publication->link)
                                <a href="{{ $publication->link }}" target="_blank" rel="noopener" class="text-gray-600 hover:text-gray-900 font-medium">
                                    {{ $publication->name }}
                                </a>
                            @else
                                <span class="text-gray-600 font-medium">{{ $publication->name }}</span>
                            @endif
                        @endforeach
                    </div>
                    <div class="text-center mt-8">
                        <a href="{{ route('publications.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">See all publications</a>
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-public-layout>

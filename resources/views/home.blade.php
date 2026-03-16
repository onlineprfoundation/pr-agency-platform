<x-public-layout>
    {{-- Hero --}}
    <section class="relative py-20 sm:py-28 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-gray-50 via-white to-gray-100"></div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23000000\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-6 tracking-tight">
                    {{ \App\Models\Setting::get('home_hero_title') ?: \App\Models\Setting::get('site_name', config('app.name')) }}
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    {{ \App\Models\Setting::get('home_hero_subtitle') ?: \App\Models\Setting::get('tagline', 'Professional PR services for your brand.') }}
                </p>
                <div class="mt-10 flex flex-wrap gap-4 justify-center">
                    <a href="{{ route('packages.index') }}" class="inline-flex items-center px-6 py-3.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700 font-medium shadow-lg shadow-gray-800/20 transition">
                        {{ \App\Models\Setting::get('home_hero_cta_text') ?: 'View Packages' }}
                    </a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center px-6 py-3.5 border-2 border-gray-300 rounded-lg hover:border-gray-400 hover:bg-gray-50 font-medium transition">
                        Get in Touch
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- What is Digital PR --}}
    <section class="py-16 sm:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">What is Digital PR?</h2>
                <p class="text-lg text-gray-600 leading-relaxed">
                    Digital PR builds your brand's online visibility through earned media—press releases, guest posts, journalist outreach, and high-authority placements. Unlike paid ads, it earns credibility and lasting SEO value.
                </p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-6 rounded-xl bg-gray-50 border border-gray-100">
                    <div class="text-2xl font-bold text-gray-800 mb-2">Authority</div>
                    <p class="text-gray-600 text-sm">Build trust with placements on respected publications and industry sites.</p>
                </div>
                <div class="p-6 rounded-xl bg-gray-50 border border-gray-100">
                    <div class="text-2xl font-bold text-gray-800 mb-2">SEO Backlinks</div>
                    <p class="text-gray-600 text-sm">Quality dofollow links that improve search rankings and domain authority.</p>
                </div>
                <div class="p-6 rounded-xl bg-gray-50 border border-gray-100">
                    <div class="text-2xl font-bold text-gray-800 mb-2">Reach</div>
                    <p class="text-gray-600 text-sm">Extend your audience beyond paid channels with organic traffic and referrals.</p>
                </div>
                <div class="p-6 rounded-xl bg-gray-50 border border-gray-100">
                    <div class="text-2xl font-bold text-gray-800 mb-2">Credibility</div>
                    <p class="text-gray-600 text-sm">Third-party validation that ads and owned content cannot replicate.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="py-16 sm:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Why Choose Us</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-gray-800 text-white font-bold text-xl mb-4">1</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Quality Placements</h3>
                    <p class="text-gray-600">We partner with vetted publications and ensure placements that match your goals.</p>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-gray-800 text-white font-bold text-xl mb-4">2</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Transparent Pricing</h3>
                    <p class="text-gray-600">Clear packages and pricing—no hidden fees. Request a quote for custom needs.</p>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-gray-800 text-white font-bold text-xl mb-4">3</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Proven Results</h3>
                    <p class="text-gray-600">Track record of successful campaigns and satisfied clients across industries.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Packages Preview --}}
    @if(\App\Models\Setting::get('home_show_packages', '1') === '1' && $packages->isNotEmpty())
        <section class="py-16 sm:py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Our Packages</h2>
                    <p class="text-gray-600">Choose the right plan for your PR goals</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    @foreach($packages as $package)
                        <div class="group bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-gray-200 transition-all duration-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $package->name }}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ Str::limit($package->description, 120) }}</p>
                            <p class="text-xl font-bold text-gray-900 mb-4">{{ $package->formatted_price }}</p>
                            <a href="{{ route('packages.show', $package) }}" class="inline-flex items-center text-gray-800 font-medium hover:text-gray-900 group-hover:underline">
                                Learn more
                                <svg class="w-4 h-4 ml-1 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-10">
                    <a href="{{ route('packages.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-700 font-medium transition">
                        View all packages
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- Publications Preview --}}
    @if(\App\Models\Setting::get('home_show_publications', '1') === '1' && $publications->isNotEmpty())
        <section class="py-16 sm:py-24 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">We Work With</h2>
                    <p class="text-gray-600">Trusted publications and outlets for your brand</p>
                </div>
                <div class="flex flex-wrap justify-center gap-8 items-center">
                    @foreach($publications as $publication)
                        @if($publication->link)
                            <a href="{{ $publication->link }}" target="_blank" rel="noopener" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-gray-200 hover:border-gray-300 hover:shadow-md transition font-medium text-gray-700 hover:text-gray-900">
                                @if($publication->logo_path)
                                    <img src="{{ asset('storage/' . $publication->logo_path) }}" alt="{{ $publication->name }}" class="h-8 object-contain">
                                @endif
                                {{ $publication->name }}
                            </a>
                        @else
                            <span class="flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-gray-200 font-medium text-gray-600">
                                @if($publication->logo_path)
                                    <img src="{{ asset('storage/' . $publication->logo_path) }}" alt="{{ $publication->name }}" class="h-8 object-contain">
                                @endif
                                {{ $publication->name }}
                            </span>
                        @endif
                    @endforeach
                </div>
                <div class="text-center mt-10">
                    <a href="{{ route('publications.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 font-medium">
                        See all publications
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- CTA Strip --}}
    <section class="py-16 sm:py-20 bg-gray-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Ready to grow your brand?</h2>
            <p class="text-gray-300 mb-8">Get in touch for a custom quote or explore our packages.</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('quote') }}" class="inline-flex items-center px-6 py-3.5 bg-white text-gray-800 rounded-lg hover:bg-gray-100 font-medium transition">
                    Request Quote
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center px-6 py-3.5 border-2 border-white text-white rounded-lg hover:bg-white/10 font-medium transition">
                    Contact Us
                </a>
            </div>
        </div>
    </section>
</x-public-layout>

<x-public-layout>
    <div class="py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Page Header --}}
            <div class="text-center mb-16">
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 tracking-tight">Publications</h1>
                <p class="text-xl text-gray-600 mt-4 max-w-2xl mx-auto">We work with these trusted outlets and placements for your brand.</p>
            </div>

            @if($publications->isEmpty())
                <div class="max-w-xl mx-auto text-center py-16 px-6 rounded-2xl bg-gray-50 border border-gray-200">
                    <p class="text-gray-600 text-lg">No publications listed yet.</p>
                    <p class="text-gray-500 mt-2">Check back soon or <a href="{{ route('contact') }}" class="text-gray-800 hover:underline font-medium">contact us</a> to learn about our network.</p>
                </div>
            @else
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($publications as $publication)
                        <div class="group bg-white rounded-2xl shadow-md border border-gray-200 p-6 hover:shadow-xl hover:border-gray-300 transition-all duration-200 flex flex-col">
                            {{-- Logo --}}
                            <div class="flex items-center justify-center h-24 mb-4 bg-gray-50 rounded-xl overflow-hidden">
                                @if($publication->logo_path)
                                    <img src="{{ asset('storage/' . $publication->logo_path) }}" alt="{{ $publication->name }}" class="max-h-20 max-w-full object-contain">
                                @else
                                    <span class="text-2xl font-bold text-gray-300">{{ substr($publication->name, 0, 2) }}</span>
                                @endif
                            </div>
                            <h3 class="font-bold text-gray-900 text-center">{{ $publication->name }}</h3>
                            @if($publication->genre || $publication->region)
                                <p class="text-sm text-gray-500 mt-1 text-center">
                                    @if($publication->genre){{ $publication->genre }}@endif
                                    @if($publication->genre && $publication->region) · @endif
                                    @if($publication->region){{ $publication->region }}@endif
                                </p>
                            @endif
                            {{-- Badges --}}
                            <div class="mt-4 flex flex-wrap gap-2 justify-center">
                                @if($publication->formatted_price)
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg">{{ $publication->formatted_price }}</span>
                                @endif
                                @if($publication->words_allowed)
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg">{{ $publication->words_allowed }} words</span>
                                @endif
                                @if($publication->da)
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg">DA {{ $publication->da }}</span>
                                @endif
                                @if($publication->dofollow)
                                    <span class="px-2.5 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-lg">DoFollow</span>
                                @endif
                            </div>
                            {{-- Visit Link --}}
                            @if($publication->link)
                                <a href="{{ $publication->link }}" target="_blank" rel="noopener" class="mt-6 flex items-center justify-center gap-2 px-4 py-2.5 border-2 border-gray-300 rounded-lg hover:border-gray-400 hover:bg-gray-50 font-medium text-sm transition">
                                    Visit
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-public-layout>

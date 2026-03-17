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
                        <div class="group bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden hover:shadow-xl hover:border-gray-300 transition-all duration-200 flex flex-col">
                            <a href="{{ route('publications.show', $publication) }}" class="block p-6 flex flex-col flex-1">
                                {{-- Logo --}}
                                @if($publication->logo_path)
                                <div class="flex items-center justify-center h-24 mb-4 bg-gray-50 rounded-xl overflow-hidden">
                                    <img src="{{ asset('storage/' . $publication->logo_path) }}" alt="{{ $publication->name }}" class="max-h-20 max-w-full object-contain">
                                </div>
                                @endif
                                <h3 class="font-bold text-gray-900 text-center">{{ $publication->name }}</h3>
                                @if($publication->genre || $publication->region)
                                    <p class="text-sm text-gray-500 mt-1 text-center">
                                        @if($publication->genre){{ $publication->genre }}@endif
                                        @if($publication->genre && $publication->region) · @endif
                                        @if($publication->region){{ $publication->region }}@endif
                                    </p>
                                @endif
                                {{-- Badges: all available details --}}
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
                                    @if($publication->traffic)
                                        <span class="px-2.5 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg">{{ number_format($publication->traffic) }} traffic</span>
                                    @endif
                                    @if($publication->tat)
                                        <span class="px-2.5 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg">{{ $publication->tat }}</span>
                                    @endif
                                    @if($publication->backlinks_count)
                                        <span class="px-2.5 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg">{{ $publication->backlinks_count }} links</span>
                                    @endif
                                    @if($publication->dofollow)
                                        <span class="px-2.5 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-lg">DoFollow</span>
                                    @endif
                                    @if($publication->indexed)
                                        <span class="px-2.5 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-lg">Indexed</span>
                                    @endif
                                </div>
                                @if($publication->disclaimer)
                                    <p class="mt-3 text-xs text-gray-500 line-clamp-2">{{ Str::limit($publication->disclaimer, 80) }}</p>
                                @endif
                            </a>
                            <div class="p-4 border-t border-gray-100 flex gap-2">
                                <a href="{{ route('publications.show', $publication) }}" class="flex-1 text-center px-3 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700">View details</a>
                                @if($publication->hasPrice())
                                    <a href="{{ route('publications.checkout.create', $publication) }}" class="flex-1 text-center px-3 py-2 bg-gray-700 text-white text-sm font-medium rounded-lg hover:bg-gray-600">Purchase</a>
                                @endif
                                <a href="{{ route('quote', ['publication_id' => $publication->id]) }}" class="flex-1 text-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg hover:bg-gray-50">Request quote</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-public-layout>

<x-public-layout>
    <div class="py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('publications.index') }}" class="text-gray-600 hover:text-gray-900 mb-6 inline-block">← Back to publications</a>

            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="p-8">
                    <div class="flex flex-col sm:flex-row gap-6">
                        @if($publication->logo_path)
                        <div class="shrink-0 flex items-center justify-center w-32 h-32 bg-gray-50 rounded-xl overflow-hidden">
                            <img src="{{ asset('storage/' . $publication->logo_path) }}" alt="{{ $publication->name }}" class="max-h-24 max-w-full object-contain">
                        </div>
                        @endif
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $publication->name }}</h1>
                            @if($publication->genre || $publication->region)
                                <p class="text-gray-600 mt-1">{{ $publication->genre }}@if($publication->genre && $publication->region) · @endif{{ $publication->region }}</p>
                            @endif
                            <div class="flex flex-wrap gap-2 mt-4">
                                @if($publication->formatted_price)
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 font-semibold rounded-lg">{{ $publication->formatted_price }}</span>
                                @endif
                                @if($publication->words_allowed)
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg">{{ $publication->words_allowed }} words</span>
                                @endif
                                @if($publication->da)
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg">DA {{ $publication->da }}</span>
                                @endif
                                @if($publication->dofollow)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 font-medium rounded-lg">DoFollow</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <dl class="mt-8 grid sm:grid-cols-2 gap-4">
                        @if($publication->tat)
                            <div><dt class="text-sm text-gray-500">Turnaround Time</dt><dd class="font-medium">{{ $publication->tat }}</dd></div>
                        @endif
                        @if($publication->backlinks_count !== null)
                            <div><dt class="text-sm text-gray-500">Backlinks</dt><dd class="font-medium">{{ $publication->backlinks_count }}</dd></div>
                        @endif
                        @if($publication->traffic)
                            <div><dt class="text-sm text-gray-500">Traffic</dt><dd class="font-medium">{{ number_format($publication->traffic) }}/mo</dd></div>
                        @endif
                        @if($publication->indexed)
                            <div><dt class="text-sm text-gray-500">Indexed</dt><dd class="font-medium">Yes</dd></div>
                        @endif
                        @if($publication->disclaimer)
                            <div class="sm:col-span-2"><dt class="text-sm text-gray-500">Disclaimer</dt><dd class="text-gray-700">{{ $publication->disclaimer }}</dd></div>
                        @endif
                    </dl>

                    @if($publication->link)
                        <a href="{{ $publication->link }}" target="_blank" rel="noopener" class="mt-6 inline-flex items-center gap-2 px-4 py-2.5 border-2 border-gray-300 rounded-lg hover:border-gray-400 hover:bg-gray-50 font-medium text-sm transition">
                            Visit publication
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    @endif

                    <div class="mt-8 pt-6 border-t border-gray-200 flex flex-wrap gap-3">
                        @if($publication->hasPrice())
                            <a href="{{ route('publications.checkout.create', $publication) }}" class="inline-flex items-center px-6 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-700 font-medium">
                                Purchase – {{ $publication->formatted_price }}
                            </a>
                        @endif
                        <a href="{{ route('quote', ['publication_id' => $publication->id]) }}" class="inline-flex items-center px-6 py-3 {{ $publication->hasPrice() ? 'border-2 border-gray-300 rounded-lg hover:bg-gray-50 font-medium' : 'bg-gray-800 text-white rounded-lg hover:bg-gray-700 font-medium' }}">
                            Request quote
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>

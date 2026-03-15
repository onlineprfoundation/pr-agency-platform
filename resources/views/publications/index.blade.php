<x-public-layout>
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-3xl font-bold text-gray-900">Publications</h1>
                <p class="text-gray-600 mt-2">We work with these outlets and placements</p>
            </div>

            @if($publications->isEmpty())
                <p class="text-center text-gray-600">No publications listed yet.</p>
            @else
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($publications as $publication)
                        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                            @if($publication->logo_path)
                                <img src="{{ asset('storage/' . $publication->logo_path) }}" alt="{{ $publication->name }}" class="h-16 mx-auto mb-4 object-contain">
                            @endif
                            <h3 class="font-semibold text-gray-900">{{ $publication->name }}</h3>
                            @if($publication->genre || $publication->region)
                                <p class="text-sm text-gray-500 mt-1">
                                    @if($publication->genre){{ $publication->genre }}@endif
                                    @if($publication->genre && $publication->region) · @endif
                                    @if($publication->region){{ $publication->region }}@endif
                                </p>
                            @endif
                            <div class="mt-3 flex flex-wrap gap-2 text-sm">
                                @if($publication->formatted_price)
                                    <span class="px-2 py-0.5 bg-gray-100 rounded">{{ $publication->formatted_price }}</span>
                                @endif
                                @if($publication->words_allowed)
                                    <span class="px-2 py-0.5 bg-gray-100 rounded">{{ $publication->words_allowed }} words</span>
                                @endif
                                @if($publication->da)
                                    <span class="px-2 py-0.5 bg-gray-100 rounded">DA {{ $publication->da }}</span>
                                @endif
                                @if($publication->dofollow)
                                    <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded">DoFollow</span>
                                @endif
                            </div>
                            @if($publication->link)
                                <a href="{{ $publication->link }}" target="_blank" rel="noopener" class="text-sm text-gray-600 hover:text-gray-900 mt-3 inline-block">Visit →</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-public-layout>

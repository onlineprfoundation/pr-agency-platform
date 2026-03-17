<x-public-layout>
    <div class="py-16">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-8 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Thank you for your purchase!</h1>
                @if($package ?? null)
                    <p class="text-gray-600 mb-2">You purchased: <strong>{{ $package->name }}</strong></p>
                    <p class="text-lg font-semibold text-gray-800 mb-4">{{ $package->formatted_price }}</p>
                @endif
                <p class="text-gray-600 mb-8">Your payment has been received. We will be in touch shortly.</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-700 font-medium">
                        Return to homepage
                    </a>
                    <a href="{{ route('packages.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">
                        View packages
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>

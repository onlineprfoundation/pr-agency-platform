<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 text-center">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <div class="mb-4">
                    <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Payment successful</h2>
                <p class="text-gray-600 mb-6">Thank you for your payment. Your invoice has been marked as paid.</p>
                <a href="{{ route('home') }}" class="inline-flex px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Return to home</a>
            </div>
        </div>
    </div>
</x-app-layout>

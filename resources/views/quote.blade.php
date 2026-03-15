<x-public-layout>
    @if($recaptchaEnabled ?? false)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <div class="py-16">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-3xl font-bold text-gray-900">Request a Quote</h1>
                <p class="text-gray-600 mt-2">Tell us about your project and we'll get back to you</p>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-100">
                <form action="{{ route('quote.store') }}" method="POST" class="space-y-6">
                    @csrf
                    @if(request('package'))
                        <input type="hidden" name="package_interest" value="{{ request('package') }}">
                        <p class="text-sm text-gray-600">Requesting quote for: <strong>{{ request('package') }}</strong></p>
                    @endif
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500">
                    </div>
                    <div>
                        <label for="company" class="block text-sm font-medium text-gray-700">Company</label>
                        <input type="text" name="company" id="company" value="{{ old('company') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500">
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700">Tell us about your project</label>
                        <textarea name="message" id="message" rows="5"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @if($recaptchaEnabled ?? false)
                        <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
                        @error('g-recaptcha-response')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    @endif
                    <button type="submit" class="w-full px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 font-medium">
                        Request Quote
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-public-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Settings</h2>
    </x-slot>

    <div class="py-12" x-data="{ tab: 'general' }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            <div class="flex gap-2 mb-6 border-b border-gray-200">
                <button type="button" @click="tab = 'general'" :class="tab === 'general' ? 'border-b-2 border-gray-800 text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2 text-sm font-medium">General</button>
                <button type="button" @click="tab = 'payment'" :class="tab === 'payment' ? 'border-b-2 border-gray-800 text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2 text-sm font-medium">Payment</button>
                <button type="button" @click="tab = 'email'" :class="tab === 'email' ? 'border-b-2 border-gray-800 text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2 text-sm font-medium">Email</button>
                <button type="button" @click="tab = 'homepage'" :class="tab === 'homepage' ? 'border-b-2 border-gray-800 text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2 text-sm font-medium">Homepage</button>
                <button type="button" @click="tab = 'pages'" :class="tab === 'pages' ? 'border-b-2 border-gray-800 text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2 text-sm font-medium">Pages</button>
                <button type="button" @click="tab = 'security'" :class="tab === 'security' ? 'border-b-2 border-gray-800 text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2 text-sm font-medium">Security</button>
            </div>

            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div x-show="tab === 'general'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">
                    <div>
                        <label for="site_name" class="block text-sm font-medium text-gray-700">Site name *</label>
                        <input type="text" name="site_name" id="site_name" value="{{ old('site_name', $settings['site_name']) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300">
                    </div>
                    <div>
                        <label for="tagline" class="block text-sm font-medium text-gray-700">Tagline</label>
                        <input type="text" name="tagline" id="tagline" value="{{ old('tagline', $settings['tagline']) }}"
                            class="mt-1 block w-full rounded-md border-gray-300">
                    </div>
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700">Contact email</label>
                        <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}"
                            class="mt-1 block w-full rounded-md border-gray-300">
                    </div>
                    <div>
                        <label for="contact_address" class="block text-sm font-medium text-gray-700">Contact address</label>
                        <textarea name="contact_address" id="contact_address" rows="2" class="mt-1 block w-full rounded-md border-gray-300">{{ old('contact_address', $settings['contact_address']) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Logo</label>
                        @if($settings['logo_path'])
                            <div class="mt-1 flex items-center gap-4">
                                <img src="{{ Storage::url($settings['logo_path']) }}" alt="Logo" class="h-12 object-contain">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="remove_logo" value="1">
                                    <span class="text-sm text-gray-500">Remove logo</span>
                                </label>
                            </div>
                        @endif
                        <input type="file" name="logo" accept=".jpeg,.jpg,.png,.gif,.webp,.svg" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-gray-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Favicon</label>
                        @if($settings['favicon_path'])
                            <div class="mt-1 flex items-center gap-4">
                                <img src="{{ Storage::url($settings['favicon_path']) }}" alt="Favicon" class="h-8 w-8 object-contain">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="remove_favicon" value="1">
                                    <span class="text-sm text-gray-500">Remove favicon</span>
                                </label>
                            </div>
                        @endif
                        <input type="file" name="favicon" accept=".ico,.png,.gif,.svg" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-gray-100">
                    </div>
                </div>

                <div x-show="tab === 'payment'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">
                    <div>
                        <label for="services_stripe_key" class="block text-sm font-medium text-gray-700">Stripe publishable key</label>
                        <input type="text" name="services_stripe_key" id="services_stripe_key" value="{{ old('services_stripe_key', $settings['services_stripe_key']) }}"
                            class="mt-1 block w-full rounded-md border-gray-300" placeholder="pk_live_...">
                    </div>
                    <div>
                        <label for="services_stripe_secret" class="block text-sm font-medium text-gray-700">Stripe secret key</label>
                        <input type="password" name="services_stripe_secret" id="services_stripe_secret"
                            class="mt-1 block w-full rounded-md border-gray-300" placeholder="Leave blank to keep current" autocomplete="new-password">
                    </div>
                    <div>
                        <label for="services_stripe_webhook_secret" class="block text-sm font-medium text-gray-700">Stripe webhook secret</label>
                        <input type="password" name="services_stripe_webhook_secret" id="services_stripe_webhook_secret"
                            class="mt-1 block w-full rounded-md border-gray-300" placeholder="Leave blank to keep current" autocomplete="new-password">
                    </div>
                    <div>
                        <label for="services_stripe_currency" class="block text-sm font-medium text-gray-700">Currency</label>
                        <input type="text" name="services_stripe_currency" id="services_stripe_currency" value="{{ old('services_stripe_currency', $settings['services_stripe_currency']) }}"
                            class="mt-1 block w-full rounded-md border-gray-300" placeholder="usd">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Webhook URL</label>
                        <p class="mt-1 text-sm text-gray-500">Add this URL to your Stripe webhook settings:</p>
                        <code class="block mt-2 p-2 bg-gray-100 rounded text-sm break-all">{{ url('/webhook/stripe') }}</code>
                    </div>
                </div>

                <div x-show="tab === 'email'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">
                    <div>
                        <label for="mail_driver" class="block text-sm font-medium text-gray-700">Mail driver</label>
                        <select name="mail_driver" id="mail_driver" class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="log" {{ $settings['mail_driver'] === 'log' ? 'selected' : '' }}>Log (no emails sent)</option>
                            <option value="smtp" {{ $settings['mail_driver'] === 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="mailgun" {{ $settings['mail_driver'] === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                            <option value="ses" {{ $settings['mail_driver'] === 'ses' ? 'selected' : '' }}>Amazon SES</option>
                            <option value="postmark" {{ $settings['mail_driver'] === 'postmark' ? 'selected' : '' }}>Postmark</option>
                            <option value="resend" {{ $settings['mail_driver'] === 'resend' ? 'selected' : '' }}>Resend</option>
                            <option value="sendmail" {{ $settings['mail_driver'] === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                        </select>
                    </div>
                    <div>
                        <label for="mail_host" class="block text-sm font-medium text-gray-700">SMTP host</label>
                        <input type="text" name="mail_host" id="mail_host" value="{{ old('mail_host', $settings['mail_host']) }}"
                            class="mt-1 block w-full rounded-md border-gray-300" placeholder="smtp.example.com">
                    </div>
                    <div>
                        <label for="mail_port" class="block text-sm font-medium text-gray-700">SMTP port</label>
                        <input type="text" name="mail_port" id="mail_port" value="{{ old('mail_port', $settings['mail_port']) }}"
                            class="mt-1 block w-full rounded-md border-gray-300" placeholder="587">
                    </div>
                    <div>
                        <label for="mail_username" class="block text-sm font-medium text-gray-700">SMTP username</label>
                        <input type="text" name="mail_username" id="mail_username" value="{{ old('mail_username', $settings['mail_username']) }}"
                            class="mt-1 block w-full rounded-md border-gray-300">
                    </div>
                    <div>
                        <label for="mail_password" class="block text-sm font-medium text-gray-700">SMTP password</label>
                        <input type="password" name="mail_password" id="mail_password"
                            class="mt-1 block w-full rounded-md border-gray-300" placeholder="Leave blank to keep current" autocomplete="new-password">
                    </div>
                    <div>
                        <label for="mail_encryption" class="block text-sm font-medium text-gray-700">Encryption</label>
                        <select name="mail_encryption" id="mail_encryption" class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="tls" {{ $settings['mail_encryption'] === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ $settings['mail_encryption'] === 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="none" {{ $settings['mail_encryption'] === 'none' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                    <div>
                        <label for="mail_from_address" class="block text-sm font-medium text-gray-700">From address</label>
                        <input type="email" name="mail_from_address" id="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address']) }}"
                            class="mt-1 block w-full rounded-md border-gray-300" placeholder="noreply@example.com">
                    </div>
                    <div>
                        <label for="mail_from_name" class="block text-sm font-medium text-gray-700">From name</label>
                        <input type="text" name="mail_from_name" id="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name']) }}"
                            class="mt-1 block w-full rounded-md border-gray-300" placeholder="{{ config('app.name') }}">
                    </div>
                </div>

                <div x-show="tab === 'homepage'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">
                    <div>
                        <label for="home_hero_title" class="block text-sm font-medium text-gray-700">Hero title</label>
                        <input type="text" name="home_hero_title" id="home_hero_title" value="{{ old('home_hero_title', $settings['home_hero_title']) }}"
                            class="mt-1 block w-full rounded-md border-gray-300" placeholder="Leave blank to use site name">
                    </div>
                    <div>
                        <label for="home_hero_subtitle" class="block text-sm font-medium text-gray-700">Hero subtitle</label>
                        <textarea name="home_hero_subtitle" id="home_hero_subtitle" rows="2" class="mt-1 block w-full rounded-md border-gray-300">{{ old('home_hero_subtitle', $settings['home_hero_subtitle']) }}</textarea>
                    </div>
                    <div>
                        <label for="home_hero_cta_text" class="block text-sm font-medium text-gray-700">Hero CTA button text</label>
                        <input type="text" name="home_hero_cta_text" id="home_hero_cta_text" value="{{ old('home_hero_cta_text', $settings['home_hero_cta_text']) }}"
                            class="mt-1 block w-full rounded-md border-gray-300" placeholder="View Packages">
                    </div>
                    <div>
                        <label class="flex items-center gap-2">
                            <input type="hidden" name="home_show_packages" value="0">
                            <input type="checkbox" name="home_show_packages" value="1" {{ old('home_show_packages', $settings['home_show_packages']) === '1' ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-700">Show packages section</span>
                        </label>
                    </div>
                    <div>
                        <label class="flex items-center gap-2">
                            <input type="hidden" name="home_show_publications" value="0">
                            <input type="checkbox" name="home_show_publications" value="1" {{ old('home_show_publications', $settings['home_show_publications']) === '1' ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-700">Show publications section</span>
                        </label>
                    </div>
                </div>

                <div x-show="tab === 'pages'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600 mb-4">Manage custom pages (About, Terms, Privacy, etc.) for your public site. Pages are available at /p/your-slug</p>
                    <a href="{{ route('admin.pages.index') }}" class="inline-flex px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Manage pages</a>
                </div>

                <div x-show="tab === 'security'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">
                    <h3 class="font-semibold text-gray-900">Google reCAPTCHA</h3>
                    <p class="text-sm text-gray-500">Protect contact and quote forms from spam. Get keys at <a href="https://www.google.com/recaptcha/admin" target="_blank" rel="noopener" class="text-blue-600 hover:underline">google.com/recaptcha/admin</a></p>
                    <div>
                        <label class="flex items-center gap-2">
                            <input type="hidden" name="recaptcha_enabled" value="0">
                            <input type="checkbox" name="recaptcha_enabled" value="1" {{ old('recaptcha_enabled', $settings['recaptcha_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-700">Enable reCAPTCHA</span>
                        </label>
                    </div>
                    <div>
                        <label for="recaptcha_site_key" class="block text-sm font-medium text-gray-700">Site key (public)</label>
                        <input type="text" name="recaptcha_site_key" id="recaptcha_site_key" value="{{ old('recaptcha_site_key', $settings['recaptcha_site_key'] ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300" placeholder="6Lc...">
                    </div>
                    <div>
                        <label for="recaptcha_secret_key" class="block text-sm font-medium text-gray-700">Secret key</label>
                        <input type="password" name="recaptcha_secret_key" id="recaptcha_secret_key"
                            class="mt-1 block w-full rounded-md border-gray-300" placeholder="Leave blank to keep current" autocomplete="new-password">
                    </div>
                    <div>
                        <label for="recaptcha_forms" class="block text-sm font-medium text-gray-700">Forms to protect</label>
                        <input type="text" name="recaptcha_forms" id="recaptcha_forms" value="{{ old('recaptcha_forms', $settings['recaptcha_forms'] ?? 'contact,quote') }}"
                            class="mt-1 block w-full rounded-md border-gray-300" placeholder="contact,quote,login">
                        <p class="mt-1 text-xs text-gray-500">Comma-separated: contact, quote, login</p>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

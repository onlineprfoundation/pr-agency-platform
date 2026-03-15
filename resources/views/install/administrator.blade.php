<x-install-layout :step="true">
    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="mb-8">
            <a href="{{ route('install.database') }}" class="text-gray-600 hover:text-gray-900 text-sm">← Back</a>
        </div>

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Administrator & App Settings</h2>
            <p class="text-gray-600 mt-2">Create your admin account and configure the application.</p>
        </div>

        <form id="install-form" class="space-y-6">
            @csrf
            <div>
                <h3 class="font-semibold text-gray-900 mb-4">Application</h3>
                <div class="space-y-4">
                    <div>
                        <label for="app_name" class="block text-sm font-medium text-gray-700">Site Name *</label>
                        <input type="text" name="app_name" id="app_name" value="{{ old('app_name', 'Online PR') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300">
                    </div>
                    <div>
                        <label for="app_url" class="block text-sm font-medium text-gray-700">Site URL *</label>
                        <input type="url" name="app_url" id="app_url" value="{{ old('app_url', url('/')) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300" placeholder="https://yoursite.com">
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900 mb-4">Administrator Account</h3>
                <div class="space-y-4">
                    <div>
                        <label for="admin_name" class="block text-sm font-medium text-gray-700">Name *</label>
                        <input type="text" name="admin_name" id="admin_name" value="{{ old('admin_name') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300">
                    </div>
                    <div>
                        <label for="admin_email" class="block text-sm font-medium text-gray-700">Email *</label>
                        <input type="email" name="admin_email" id="admin_email" value="{{ old('admin_email') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300">
                    </div>
                    <div>
                        <label for="admin_password" class="block text-sm font-medium text-gray-700">Password *</label>
                        <input type="password" name="admin_password" id="admin_password" required
                            class="mt-1 block w-full rounded-md border-gray-300">
                    </div>
                    <div>
                        <label for="admin_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password *</label>
                        <input type="password" name="admin_password_confirmation" id="admin_password_confirmation" required
                            class="mt-1 block w-full rounded-md border-gray-300">
                    </div>
                </div>
            </div>

            <div id="install-error" class="hidden p-4 bg-red-50 text-red-800 rounded-md"></div>
            <div id="install-progress" class="hidden p-4 bg-blue-50 text-blue-800 rounded-md">
                Installing... Please wait.
            </div>

            <button type="submit" id="install-btn" class="w-full px-6 py-3 bg-gray-800 text-white rounded-md hover:bg-gray-700 font-medium">
                Install Online PR
            </button>
        </form>
    </div>

    <script>
        document.getElementById('install-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('install-btn');
            const err = document.getElementById('install-error');
            const progress = document.getElementById('install-progress');

            btn.disabled = true;
            err.classList.add('hidden');
            progress.classList.remove('hidden');

            const form = document.getElementById('install-form');
            const fd = new FormData(form);

            try {
                const r = await fetch('{{ route("install.run") }}', {
                    method: 'POST',
                    body: fd,
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
                });
                const data = await r.json();

                if (data.success) {
                    window.location.href = '{{ route("install.complete") }}';
                } else {
                    err.textContent = data.message || 'Installation failed.';
                    err.classList.remove('hidden');
                    progress.classList.add('hidden');
                    btn.disabled = false;
                }
            } catch (e) {
                err.textContent = e.message || 'An error occurred.';
                err.classList.remove('hidden');
                progress.classList.add('hidden');
                btn.disabled = false;
            }
        });
    </script>
</x-install-layout>

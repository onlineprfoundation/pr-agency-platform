<x-install-layout :step="true">
    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="mb-8">
            <a href="{{ route('install.welcome') }}" class="text-gray-600 hover:text-gray-900 text-sm">← Back</a>
        </div>

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Database Configuration</h2>
            <p class="text-gray-600 mt-2">Configure your database connection.</p>
        </div>

        <form id="db-form" method="POST" action="{{ route('install.database') }}" class="space-y-6">
            @csrf
            <div>
                <label for="db_connection" class="block text-sm font-medium text-gray-700">Database Type</label>
                <select name="db_connection" id="db_connection" class="mt-1 block w-full rounded-md border-gray-300">
                    <option value="sqlite">SQLite (easiest)</option>
                    <option value="mysql">MySQL</option>
                    <option value="pgsql">PostgreSQL</option>
                </select>
            </div>

            <input type="hidden" name="db_database" id="db_database_value" value="{{ database_path('database.sqlite') }}">

            <div id="sqlite-fields">
                <div>
                    <label for="db_database_sqlite" class="block text-sm font-medium text-gray-700">Database File Path</label>
                    <input type="text" id="db_database_sqlite" value="{{ database_path('database.sqlite') }}"
                        class="mt-1 block w-full rounded-md border-gray-300" placeholder="{{ database_path('database.sqlite') }}">
                </div>
            </div>

            <div id="mysql-fields" class="space-y-4 hidden">
                <div>
                    <label for="db_host" class="block text-sm font-medium text-gray-700">Host</label>
                    <input type="text" name="db_host" id="db_host" value="127.0.0.1"
                        class="mt-1 block w-full rounded-md border-gray-300">
                </div>
                <div>
                    <label for="db_port" class="block text-sm font-medium text-gray-700">Port</label>
                    <input type="text" name="db_port" id="db_port" value="3306"
                        class="mt-1 block w-full rounded-md border-gray-300">
                </div>
                <div>
                    <label for="db_database_mysql" class="block text-sm font-medium text-gray-700">Database Name</label>
                    <input type="text" id="db_database_mysql" value="laravel"
                        class="mt-1 block w-full rounded-md border-gray-300">
                </div>
                <div>
                    <label for="db_username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="db_username" id="db_username" value="root"
                        class="mt-1 block w-full rounded-md border-gray-300">
                </div>
                <div>
                    <label for="db_password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="db_password" id="db_password"
                        class="mt-1 block w-full rounded-md border-gray-300">
                </div>
            </div>

            <div id="test-result" class="hidden p-4 rounded-md"></div>

            <div class="flex gap-4">
                <button type="button" id="test-btn" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                    Test Connection
                </button>
                <button type="submit" id="continue-btn" class="hidden px-6 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                    Continue →
                </button>
            </div>
        </form>
    </div>

    <script>
        const connection = document.getElementById('db_connection');
        const sqliteFields = document.getElementById('sqlite-fields');
        const mysqlFields = document.getElementById('mysql-fields');
        const testBtn = document.getElementById('test-btn');
        const testResult = document.getElementById('test-result');
        const continueBtn = document.getElementById('continue-btn');

        function toggleFields() {
            const v = connection.value;
            sqliteFields.classList.toggle('hidden', v !== 'sqlite');
            mysqlFields.classList.toggle('hidden', v !== 'mysql' && v !== 'pgsql');
            if (v === 'pgsql') {
                document.getElementById('db_port').value = '5432';
            } else if (v === 'mysql') {
                document.getElementById('db_port').value = '3306';
            }
        }
        connection.addEventListener('change', toggleFields);
        toggleFields();

        document.getElementById('db-form').addEventListener('submit', function(e) {
            if (continueBtn.classList.contains('hidden')) {
                e.preventDefault();
            } else {
                const dbVal = document.getElementById('db_database_value');
                dbVal.value = connection.value === 'sqlite'
                    ? document.getElementById('db_database_sqlite').value
                    : document.getElementById('db_database_mysql').value;
            }
        });

        document.getElementById('db_database_sqlite').addEventListener('input', function() {
            document.getElementById('db_database_value').value = this.value;
        });
        document.getElementById('db_database_mysql').addEventListener('input', function() {
            if (connection.value !== 'sqlite') {
                document.getElementById('db_database_value').value = this.value;
            }
        });

        testBtn.addEventListener('click', async () => {
            const form = document.getElementById('db-form');
            const fd = new FormData(form);
            fd.set('db_connection', connection.value);
            fd.set('db_database', connection.value === 'sqlite'
                ? document.getElementById('db_database_sqlite').value
                : document.getElementById('db_database_mysql').value);
            if (connection.value !== 'sqlite') {
                fd.set('db_host', document.getElementById('db_host').value);
                fd.set('db_port', document.getElementById('db_port').value);
                fd.set('db_username', document.getElementById('db_username').value);
                fd.set('db_password', document.getElementById('db_password').value);
            }

            testResult.classList.remove('hidden');
            testResult.textContent = 'Testing...';
            testResult.className = 'p-4 rounded-md bg-gray-100';

            try {
                const r = await fetch('{{ route("install.test-database") }}', {
                    method: 'POST',
                    body: fd,
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
                });
                const data = await r.json();
                if (data.passed) {
                    testResult.className = 'p-4 rounded-md bg-green-50 text-green-800';
                    testResult.textContent = '✓ ' + data.message;
                    continueBtn.classList.remove('hidden');
                } else {
                    testResult.className = 'p-4 rounded-md bg-red-50 text-red-800';
                    testResult.textContent = '✗ ' + data.message;
                }
            } catch (e) {
                testResult.className = 'p-4 rounded-md bg-red-50 text-red-800';
                testResult.textContent = '✗ ' + e.message;
            }
        });
    </script>
</x-install-layout>

<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InstallService
{
    protected string $installLockPath;

    public function __construct()
    {
        $this->installLockPath = storage_path('installed');
    }

    public function isInstalled(): bool
    {
        return File::exists($this->installLockPath);
    }

    /**
     * Run requirements and permission checks.
     *
     * @return array{requirements: array, permissions: array, passed: bool}
     */
    public function checkRequirements(): array
    {
        $requirements = [];
        $permissions = [];

        // PHP version
        $phpVersion = PHP_VERSION;
        $phpOk = version_compare(PHP_VERSION, '8.2.0', '>=');
        $requirements[] = [
            'name' => 'PHP Version (>= 8.2)',
            'current' => $phpVersion,
            'passed' => $phpOk,
        ];

        // Required extensions
        $extensions = ['pdo', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo'];
        foreach ($extensions as $ext) {
            $loaded = extension_loaded($ext);
            $requirements[] = [
                'name' => "PHP Extension: {$ext}",
                'current' => $loaded ? 'Loaded' : 'Missing',
                'passed' => $loaded,
            ];
        }

        // Permissions
        $paths = [
            'storage' => storage_path(),
            'storage/framework' => storage_path('framework'),
            'storage/framework/cache' => storage_path('framework/cache'),
            'storage/framework/sessions' => storage_path('framework/sessions'),
            'storage/framework/views' => storage_path('framework/views'),
            'storage/logs' => storage_path('logs'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
        ];

        foreach ($paths as $label => $path) {
            $exists = File::exists($path);
            $writable = $exists && is_writable($path);
            $permissions[] = [
                'path' => $label,
                'full_path' => $path,
                'exists' => $exists,
                'writable' => $writable,
                'passed' => $exists && $writable,
            ];
        }

        $allRequirementsPassed = collect($requirements)->every(fn ($r) => $r['passed']);
        $allPermissionsPassed = collect($permissions)->every(fn ($p) => $p['passed']);

        return [
            'requirements' => $requirements,
            'permissions' => $permissions,
            'passed' => $allRequirementsPassed && $allPermissionsPassed,
        ];
    }

    /**
     * Test database connection.
     */
    public function testDatabaseConnection(array $config): array
    {
        try {
            $connection = $config['connection'] ?? 'sqlite';
            if ($connection === 'sqlite') {
                $database = $config['database'] ?? database_path('database.sqlite');
                if ($database === ':memory:') {
                    return ['passed' => true, 'message' => 'SQLite in-memory works.'];
                }
                if (! File::exists($database)) {
                    File::put($database, '');
                }
            }

            $dbConfig = [
                'driver' => $connection,
                'database' => $config['database'] ?? ($connection === 'sqlite' ? database_path('database.sqlite') : 'laravel'),
                'prefix' => '',
                'strict' => true,
            ];

            if ($connection !== 'sqlite') {
                $dbConfig['host'] = $config['host'] ?? '127.0.0.1';
                $dbConfig['port'] = $config['port'] ?? ($connection === 'mysql' ? 3306 : 5432);
                $dbConfig['username'] = $config['username'] ?? 'root';
                $dbConfig['password'] = $config['password'] ?? '';
                $dbConfig['charset'] = 'utf8mb4';
                $dbConfig['collation'] = 'utf8mb4_unicode_ci';
                $dbConfig['engine'] = null;
            }

            config(['database.connections.install_test' => $dbConfig]);

            DB::connection('install_test')->getPdo();
            DB::connection('install_test')->getDatabaseName();

            return ['passed' => true, 'message' => 'Connection successful.'];
        } catch (\Throwable $e) {
            return ['passed' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Run the installation.
     */
    public function install(array $data): array
    {
        try {
            $connection = $data['db_connection'] ?? 'sqlite';

            // Ensure .env exists
            $envPath = base_path('.env');
            if (! File::exists($envPath)) {
                if (! File::exists(base_path('.env.example'))) {
                    return ['success' => false, 'message' => '.env.example not found.'];
                }
                File::copy(base_path('.env.example'), $envPath);
            }

            $envContent = File::get($envPath);

            // Generate APP_KEY
            $key = 'base64:' . base64_encode(Str::random(32));
            $envContent = preg_replace('/^APP_KEY=.*/m', 'APP_KEY=' . $key, $envContent);

            // App settings
            $envContent = preg_replace('/^APP_NAME=.*/m', 'APP_NAME="' . addslashes($data['app_name'] ?? 'Online PR') . '"', $envContent);
            $envContent = preg_replace('/^APP_URL=.*/m', 'APP_URL=' . ($data['app_url'] ?? 'http://localhost'), $envContent);

            // Database
            $envContent = preg_replace('/^DB_CONNECTION=.*/m', 'DB_CONNECTION=' . $connection, $envContent);

            if ($connection === 'sqlite') {
                $dbPath = $data['db_database'] ?? database_path('database.sqlite');
                if (! File::exists($dbPath)) {
                    File::put($dbPath, '');
                }
                $envContent = preg_replace('/^DB_DATABASE=.*/m', 'DB_DATABASE=' . $dbPath, $envContent);
            } else {
                $envContent = preg_replace('/^DB_HOST=.*/m', 'DB_HOST=' . ($data['db_host'] ?? '127.0.0.1'), $envContent);
                $envContent = preg_replace('/^#?DB_PORT=.*/m', 'DB_PORT=' . ($data['db_port'] ?? '3306'), $envContent);
                $envContent = preg_replace('/^DB_DATABASE=.*/m', 'DB_DATABASE=' . ($data['db_database'] ?? 'laravel'), $envContent);
                $envContent = preg_replace('/^#?DB_USERNAME=.*/m', 'DB_USERNAME=' . ($data['db_username'] ?? 'root'), $envContent);
                $envContent = preg_replace('/^#?DB_PASSWORD=.*/m', 'DB_PASSWORD=' . ($data['db_password'] ?? ''), $envContent);
            }

            File::put($envPath, $envContent);

            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            // Run migrate in subprocess to pick up new .env
            $process = \Illuminate\Support\Facades\Process::run([
                PHP_BINARY,
                base_path('artisan'),
                'migrate',
                '--force',
            ], base_path(), timeout: 60);

            if (! $process->successful()) {
                throw new \RuntimeException('Migration failed: ' . $process->errorOutput());
            }

            Artisan::call('storage:link');

            // Create admin via subprocess (loads fresh config)
            $adminDataPath = storage_path('app/install_admin_' . Str::random(16) . '.json');
            File::put($adminDataPath, json_encode([
                'admin_name' => $data['admin_name'],
                'admin_email' => $data['admin_email'],
                'admin_password' => $data['admin_password'],
            ]));
            chmod($adminDataPath, 0600);

            $process = \Illuminate\Support\Facades\Process::run([
                PHP_BINARY,
                base_path('artisan'),
                'install:create-admin',
                $adminDataPath,
            ], base_path(), timeout: 30);

            if (! $process->successful()) {
                File::delete($adminDataPath);
                throw new \RuntimeException('Admin creation failed: ' . $process->errorOutput());
            }

            File::put($this->installLockPath, date('c'));

            // Switch to database session/cache now that migrations have run
            $envPath = base_path('.env');
            $envContent = File::get($envPath);
            $envContent = preg_replace('/^SESSION_DRIVER=.*/m', 'SESSION_DRIVER=database', $envContent);
            $envContent = preg_replace('/^CACHE_STORE=.*/m', 'CACHE_STORE=database', $envContent);
            File::put($envPath, $envContent);

            return ['success' => true, 'message' => 'Installation complete.'];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}

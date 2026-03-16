<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\Process\PhpExecutableFinder;

class InstallService
{
    protected string $installLockPath;

    /**
     * PHP CLI binary for subprocesses. When running under PHP-FPM, the resolved
     * binary may be php-fpm which cannot run artisan; use php CLI instead.
     */
    protected function phpBinary(): string
    {
        $binary = (new PhpExecutableFinder)->find(false) ?: 'php';
        if (str_contains((string) $binary, 'fpm')) {
            return 'php';
        }
        return $binary;
    }

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
     * Save database config to .env so it persists across requests (session can be lost).
     */
    public function saveDatabaseConfigToEnv(array $config): void
    {
        $envPath = base_path('.env');
        if (! File::exists($envPath) && File::exists(base_path('.env.example'))) {
            File::copy(base_path('.env.example'), $envPath);
        }
        if (! File::exists($envPath)) {
            return;
        }

        $connection = $config['connection'] ?? 'sqlite';
        $content = File::get($envPath);

        $content = preg_replace('/^DB_CONNECTION=.*/m', 'DB_CONNECTION=' . $connection, $content);

        if ($connection === 'sqlite') {
            $dbPath = $config['database'] ?? database_path('database.sqlite');
            if (! File::exists($dbPath)) {
                File::put($dbPath, '');
            }
            $content = preg_replace('/^DB_DATABASE=.*/m', 'DB_DATABASE=' . $dbPath, $content);
        } else {
            $content = preg_replace('/^#?\s*DB_HOST=.*/m', 'DB_HOST=' . ($config['host'] ?? '127.0.0.1'), $content);
            $content = preg_replace('/^#?\s*DB_PORT=.*/m', 'DB_PORT=' . ($config['port'] ?? ($connection === 'mysql' ? '3306' : '5432')), $content);
            $content = preg_replace('/^#?\s*DB_DATABASE=.*/m', 'DB_DATABASE=' . ($config['database'] ?? 'laravel'), $content);
            $content = preg_replace('/^#?\s*DB_USERNAME=.*/m', 'DB_USERNAME=' . ($config['username'] ?? 'root'), $content);
            $content = preg_replace('/^#?\s*DB_PASSWORD=.*/m', 'DB_PASSWORD=' . ($config['password'] ?? ''), $content);
        }

        File::put($envPath, $content);
    }

    /**
     * Read database config from .env (fallback when session is lost).
     */
    public function getDatabaseConfigFromEnv(): ?array
    {
        $envPath = base_path('.env');
        if (! File::exists($envPath)) {
            return null;
        }

        $vars = [];
        foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $vars[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
            }
        }

        $connection = $vars['DB_CONNECTION'] ?? 'sqlite';
        $dbDatabase = $vars['DB_DATABASE'] ?? ($connection === 'sqlite' ? database_path('database.sqlite') : 'laravel');

        return [
            'db_connection' => $connection,
            'db_database' => $dbDatabase,
            'db_host' => $vars['DB_HOST'] ?? '127.0.0.1',
            'db_port' => $vars['DB_PORT'] ?? ($connection === 'mysql' ? '3306' : '5432'),
            'db_username' => $vars['DB_USERNAME'] ?? 'root',
            'db_password' => $vars['DB_PASSWORD'] ?? '',
        ];
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
                $envContent = preg_replace('/^#?\s*DB_HOST=.*/m', 'DB_HOST=' . ($data['db_host'] ?? '127.0.0.1'), $envContent);
                $envContent = preg_replace('/^#?\s*DB_PORT=.*/m', 'DB_PORT=' . ($data['db_port'] ?? '3306'), $envContent);
                $envContent = preg_replace('/^#?\s*DB_DATABASE=.*/m', 'DB_DATABASE=' . ($data['db_database'] ?? 'laravel'), $envContent);
                $envContent = preg_replace('/^#?\s*DB_USERNAME=.*/m', 'DB_USERNAME=' . ($data['db_username'] ?? 'root'), $envContent);
                $envContent = preg_replace('/^#?\s*DB_PASSWORD=.*/m', 'DB_PASSWORD=' . ($data['db_password'] ?? ''), $envContent);
            }

            File::put($envPath, $envContent);

            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            if ($connection === 'mysql') {
                // Import schema.sql for MySQL - avoids migration ordering issues
                $schemaPath = database_path('schema.sql');
                if (File::exists($schemaPath)) {
                    $this->importSchema($data);
                } else {
                    $this->runMigrations();
                }
            } else {
                // SQLite: remove existing DB from failed installs so migrations start fresh
                $dbPath = $data['db_database'] ?? database_path('database.sqlite');
                if ($dbPath !== ':memory:' && File::exists($dbPath)) {
                    File::delete($dbPath);
                    File::put($dbPath, '');
                }
                $this->runMigrations();
            }

            File::ensureDirectoryExists(storage_path('app/public'));
            try {
                Artisan::call('storage:link');
            } catch (\Throwable $e) {
                Log::warning('storage:link failed (non-fatal): ' . $e->getMessage());
            }

            // Create admin via subprocess (loads fresh config)
            $adminDataPath = storage_path('app/install_admin_' . Str::random(16) . '.json');
            File::put($adminDataPath, json_encode([
                'admin_name' => $data['admin_name'],
                'admin_email' => $data['admin_email'],
                'admin_password' => $data['admin_password'],
            ]));
            chmod($adminDataPath, 0600);

            $process = \Illuminate\Support\Facades\Process::timeout(30)
                ->path(base_path())
                ->env(array_merge(getenv() ?: [], [
                    'PATH' => '/usr/local/bin:/usr/bin:/bin:' . (getenv('PATH') ?: ''),
                ]))
                ->run([
                    $this->phpBinary(),
                    base_path('artisan'),
                    'install:create-admin',
                    $adminDataPath,
                ]);

            if (! $process->successful()) {
                File::delete($adminDataPath);
                throw new \RuntimeException('Admin creation failed: ' . $process->errorOutput());
            }

            File::put($this->installLockPath, date('c'));

            // Keep file session/cache - more reliable, no DB table dependency
            $envPath = base_path('.env');
            $envContent = File::get($envPath);
            $envContent = preg_replace('/^SESSION_DRIVER=.*/m', 'SESSION_DRIVER=file', $envContent);
            $envContent = preg_replace('/^CACHE_STORE=.*/m', 'CACHE_STORE=file', $envContent);
            File::put($envPath, $envContent);

            return ['success' => true, 'message' => 'Installation complete.'];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Import schema.sql for MySQL (avoids migration ordering issues).
     */
    protected function importSchema(array $data): void
    {
        $schemaPath = database_path('schema.sql');
        $host = $data['db_host'] ?? '127.0.0.1';
        $port = $data['db_port'] ?? '3306';
        $user = $data['db_username'] ?? 'root';
        $pass = $data['db_password'] ?? '';
        $database = $data['db_database'] ?? 'laravel';

        $cmd = sprintf(
            'mysql -h %s -P %s -u %s %s < %s',
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($user),
            escapeshellarg($database),
            escapeshellarg($schemaPath)
        );

        $env = array_merge(getenv() ?: [], [
            'MYSQL_PWD' => $pass,
            'PATH' => '/usr/local/bin:/usr/bin:/bin:' . (getenv('PATH') ?: ''),
        ]);
        $process = \Illuminate\Support\Facades\Process::timeout(60)
            ->path(base_path())
            ->env($env)
            ->run(['sh', '-c', $cmd]);

        if (! $process->successful()) {
            $err = trim($process->errorOutput());
            $out = trim($process->output());
            $combined = $err . "\n" . $out;

            // If mysql CLI not found, fall back to migrations
            if (str_contains($combined, 'command not found') || str_contains($combined, 'No such file')) {
                $this->logInstallError('mysql CLI not found, falling back to migrations', [
                    'exit_code' => $process->exitCode(),
                    'stderr' => $err,
                    'stdout' => $out,
                ]);
                $this->runMigrations();
                return;
            }

            $msg = $err ?: $out ?: 'mysql command failed (exit ' . $process->exitCode() . ')';
            $this->logInstallError('Schema import failed', [
                'exit_code' => $process->exitCode(),
                'stderr' => $err,
                'stdout' => $out,
                'cmd' => 'mysql -h ' . $host . ' -P ' . $port . ' -u ' . $user . ' [DB]',
            ]);
            throw new \RuntimeException('Schema import failed: ' . $msg);
        }
    }

    /**
     * Run Laravel migrations (used for SQLite or when schema.sql is missing).
     */
    protected function runMigrations(): void
    {
        $process = \Illuminate\Support\Facades\Process::timeout(60)
            ->path(base_path())
            ->env(array_merge(getenv() ?: [], [
                'PATH' => '/usr/local/bin:/usr/bin:/bin:' . (getenv('PATH') ?: ''),
            ]))
            ->run([
                $this->phpBinary(),
                base_path('artisan'),
                'migrate',
                '--force',
            ]);

        if (! $process->successful()) {
            $err = trim($process->errorOutput());
            $out = trim($process->output());
            $msg = $err ?: $out ?: 'migrate command failed (exit ' . $process->exitCode() . ')';
            $this->logInstallError('Migration failed', [
                'exit_code' => $process->exitCode(),
                'stderr' => $err,
                'stdout' => $out,
            ]);
            throw new \RuntimeException('Migration failed: ' . $msg);
        }
    }

    /**
     * Log install errors to storage/logs/install.log and Laravel log.
     */
    protected function logInstallError(string $title, array $context): void
    {
        $line = $title . ' ' . json_encode($context, JSON_UNESCAPED_SLASHES);
        Log::error($line);
        $logPath = storage_path('logs/install.log');
        File::append($logPath, date('c') . ' ' . $line . "\n");
    }
}

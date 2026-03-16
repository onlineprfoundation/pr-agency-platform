<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SchemaImportCommand extends Command
{
    protected $signature = 'schema:import {--force : Skip confirmation}';

    protected $description = 'Import database/schema.sql (MySQL only). Use to fix missing tables.';

    public function handle(): int
    {
        $connection = config('database.default');
        if ($connection !== 'mysql') {
            $this->error('schema:import only supports MySQL. Current connection: ' . $connection);
            return 1;
        }

        $schemaPath = database_path('schema.sql');
        if (! File::exists($schemaPath)) {
            $this->error('schema.sql not found at ' . $schemaPath);
            return 1;
        }

        if (! $this->option('force') && ! $this->confirm('This will DROP and recreate all tables. Continue?')) {
            return 0;
        }

        $config = config('database.connections.mysql');
        $host = $config['host'] ?? '127.0.0.1';
        $port = $config['port'] ?? 3306;
        $user = $config['username'] ?? 'root';
        $pass = $config['password'] ?? '';
        $database = $config['database'] ?? 'laravel';

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
            $this->error('Import failed: ' . trim($process->errorOutput() ?: $process->output()));
            return 1;
        }

        $this->info('Schema imported successfully.');
        return 0;
    }
}

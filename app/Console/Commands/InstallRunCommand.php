<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class InstallRunCommand extends Command
{
    protected $signature = 'install:create-admin {data_file : Path to JSON file with admin_name, admin_email, admin_password}';

    protected $description = 'Create admin user during installation';

    public function handle(): int
    {
        $path = $this->argument('data_file');
        if (! File::exists($path)) {
            $this->error('Data file not found.');
            return 1;
        }

        $data = json_decode(File::get($path), true);
        if (! $data || empty($data['admin_email']) || empty($data['admin_password'])) {
            $this->error('Invalid data file.');
            return 1;
        }

        User::create([
            'name' => $data['admin_name'] ?? 'Admin',
            'email' => $data['admin_email'],
            'role' => 'admin',
            'password' => Hash::make($data['admin_password']),
        ]);

        File::delete($path);
        $this->info('Admin user created.');
        return 0;
    }
}

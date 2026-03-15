<?php

namespace App\Providers;

use App\Models\Setting;
use App\Services\ModuleService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register core modules (future: load from marketplace)
        ModuleService::register('online-pr/projects', 'Project Management', '1.0.0');
        ModuleService::register('online-pr/invoicing', 'Stripe Invoicing', '1.0.0');

        $this->applyMailSettingsFromDatabase();
    }

    protected function applyMailSettingsFromDatabase(): void
    {
        try {
            $driver = Setting::get('mail_driver');
            if ($driver && $driver !== config('mail.default')) {
                config(['mail.default' => $driver]);
            }

            if (Setting::get('mail_host')) {
                config([
                    'mail.mailers.smtp.host' => Setting::get('mail_host'),
                    'mail.mailers.smtp.port' => Setting::get('mail_port') ?: 587,
                    'mail.mailers.smtp.username' => Setting::get('mail_username'),
                    'mail.mailers.smtp.password' => Setting::get('mail_password'),
                    'mail.mailers.smtp.encryption' => Setting::get('mail_encryption') ?: 'tls',
                ]);
            }

            if (Setting::get('mail_from_address')) {
                config([
                    'mail.from.address' => Setting::get('mail_from_address'),
                    'mail.from.name' => Setting::get('mail_from_name') ?: config('app.name'),
                ]);
            }
        } catch (\Throwable $e) {
            // Settings table may not exist during install
        }
    }
}

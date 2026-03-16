<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    public static function isEnabled(): bool
    {
        return Setting::get('recaptcha_enabled', '0') === '1';
    }

    public static function isRequiredFor(string $form): bool
    {
        if (! self::isEnabled()) {
            return false;
        }

        $forms = array_map('trim', explode(',', Setting::get('recaptcha_forms', 'contact,quote')));

        return in_array($form, $forms, true);
    }

    public static function getSiteKey(): string
    {
        return (string) (Setting::get('recaptcha_site_key', '') ?: config('services.recaptcha.site_key', '') ?: '');
    }

    public static function getSecretKey(): string
    {
        return (string) (Setting::get('recaptcha_secret_key', '') ?: config('services.recaptcha.secret_key', '') ?: '');
    }

    public static function verify(string $token): bool
    {
        $secret = self::getSecretKey();
        if (empty($secret)) {
            return false;
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secret,
            'response' => $token,
        ]);

        $data = $response->json();
        return ($data['success'] ?? false) === true;
    }
}

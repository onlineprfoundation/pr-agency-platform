<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = Cache::remember('settings', 3600, function () {
            return self::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        Cache::forget('settings');
    }

    /**
     * Get setting from DB first, fall back to config (env).
     * Use dot-notation config key, e.g. 'services.stripe.key'.
     */
    public static function getConfig(string $configKey, mixed $default = null): mixed
    {
        $dbKey = str_replace('.', '_', $configKey);
        $value = self::get($dbKey);

        return $value !== null && $value !== '' ? $value : config($configKey, $default);
    }
}

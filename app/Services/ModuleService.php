<?php

namespace App\Services;

use App\Models\Module;

/**
 * Module system for future marketplace.
 * Official modules will be published by Online.PR.
 * Architecture prepared for exclusive module marketplace.
 */
class ModuleService
{
    protected static array $registry = [];

    /**
     * Register an available module (for future marketplace discovery).
     */
    public static function register(string $identifier, string $name, string $version = '1.0.0', string $source = Module::SOURCE_OFFICIAL): void
    {
        static::$registry[$identifier] = [
            'identifier' => $identifier,
            'name' => $name,
            'version' => $version,
            'source' => $source,
        ];
    }

    /**
     * Get all registered modules.
     */
    public static function available(): array
    {
        return static::$registry;
    }

    /**
     * Check if a module is enabled for this install.
     */
    public static function enabled(string $identifier): bool
    {
        return Module::isActive($identifier);
    }

    /**
     * Enable a module (creates or updates DB record).
     */
    public static function enable(string $identifier, array $config = []): Module
    {
        $info = static::$registry[$identifier] ?? [
            'name' => $identifier,
            'version' => '1.0.0',
            'source' => Module::SOURCE_OFFICIAL,
        ];

        return Module::updateOrCreate(
            ['identifier' => $identifier],
            [
                'name' => $info['name'],
                'version' => $info['version'],
                'enabled' => true,
                'config' => $config,
                'source' => $info['source'],
            ]
        );
    }

    /**
     * Disable a module.
     */
    public static function disable(string $identifier): void
    {
        Module::where('identifier', $identifier)->update(['enabled' => false]);
    }
}

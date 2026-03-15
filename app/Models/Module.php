<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'identifier',
        'name',
        'version',
        'enabled',
        'config',
        'source',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'config' => 'array',
    ];

    public const SOURCE_OFFICIAL = 'official';
    public const SOURCE_MARKETPLACE = 'marketplace';

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public static function isActive(string $identifier): bool
    {
        return static::where('identifier', $identifier)->where('enabled', true)->exists();
    }
}

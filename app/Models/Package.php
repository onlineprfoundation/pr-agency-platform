<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Package extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_cents',
        'image_path',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return $this->price_cents === null
            ? 'Quote'
            : '$' . number_format($this->price_cents / 100, 2);
    }

    public function hasPrice(): bool
    {
        return $this->price_cents !== null;
    }

    protected static function booted(): void
    {
        static::creating(function (Package $package) {
            if (empty($package->slug)) {
                $package->slug = Str::slug($package->name);
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $fillable = [
        'name',
        'logo_path',
        'link',
        'price_usd',
        'words_allowed',
        'backlinks_count',
        'tat',
        'indexed',
        'dofollow',
        'genre',
        'disclaimer',
        'region',
        'da',
        'traffic',
        'last_modified_at',
        'sort_order',
    ];

    protected $casts = [
        'indexed' => 'boolean',
        'dofollow' => 'boolean',
        'price_usd' => 'decimal:2',
        'last_modified_at' => 'datetime',
    ];

    /**
     * Ahrefs Website Authority Checker URL (Check DA).
     * Uses the publication's link as input.
     */
    public function getCheckDaUrlAttribute(): ?string
    {
        if (empty($this->link)) {
            return null;
        }

        return 'https://ahrefs.com/website-authority-checker/?input=' . urlencode($this->link);
    }

    /**
     * Ahrefs Traffic Checker URL.
     * Uses the publication's link as input.
     */
    public function getCheckTrafficUrlAttribute(): ?string
    {
        if (empty($this->link)) {
            return null;
        }

        return 'https://ahrefs.com/traffic-checker/?input=' . urlencode($this->link);
    }

    public function getFormattedPriceAttribute(): ?string
    {
        return $this->price_usd !== null
            ? '$' . number_format((float) $this->price_usd, 2)
            : null;
    }

    public function getPriceCentsAttribute(): ?int
    {
        return $this->price_usd !== null ? (int) round((float) $this->price_usd * 100) : null;
    }

    public function hasPrice(): bool
    {
        return $this->price_cents !== null && $this->price_cents >= 100;
    }

    public function publicationOrders()
    {
        return $this->hasMany(PublicationOrder::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_publication')
            ->withPivot('price_cents', 'status')
            ->withTimestamps();
    }
}

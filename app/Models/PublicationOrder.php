<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PublicationOrder extends Model
{
    protected $fillable = [
        'publication_id',
        'payment_id',
        'client_id',
        'email',
        'source',
        'status',
        'amount_cents',
        'title',
        'content',
        'featured_image_path',
        'live_link',
        'access_token',
    ];

    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function documents()
    {
        return $this->hasMany(PublicationOrderDocument::class);
    }

    public function isSubmitted(): bool
    {
        return in_array($this->status, ['submitted', 'in_progress', 'completed']);
    }

    public function canSubmit(): bool
    {
        return $this->status === 'pending_submission';
    }

    public function getFormattedAmountAttribute(): ?string
    {
        return $this->amount_cents ? '$' . number_format($this->amount_cents / 100, 2) : null;
    }

    public static function generateAccessToken(): string
    {
        return Str::random(48);
    }
}

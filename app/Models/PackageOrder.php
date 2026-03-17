<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PackageOrder extends Model
{
    protected $fillable = [
        'payment_id',
        'package_id',
        'client_id',
        'email',
        'status',
        'title',
        'content',
        'featured_image_path',
        'live_link',
        'access_token',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function documents()
    {
        return $this->hasMany(PackageOrderDocument::class);
    }

    public function isSubmitted(): bool
    {
        return in_array($this->status, ['submitted', 'in_progress', 'completed']);
    }

    public function canSubmit(): bool
    {
        return $this->status === 'pending_submission';
    }

    public static function generateAccessToken(): string
    {
        return Str::random(48);
    }
}

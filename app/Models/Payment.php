<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'package_id',
        'project_id',
        'invoice_id',
        'amount_cents',
        'email',
        'stripe_id',
        'status',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        return '$' . number_format($this->amount_cents / 100, 2);
    }
}

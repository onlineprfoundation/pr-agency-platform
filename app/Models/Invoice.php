<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'project_id',
        'amount_cents',
        'status',
        'stripe_invoice_id',
        'stripe_payment_link',
        'due_date',
        'description',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public const STATUSES = ['draft', 'sent', 'paid', 'overdue', 'cancelled'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        return '$' . number_format($this->amount_cents / 100, 2);
    }
}

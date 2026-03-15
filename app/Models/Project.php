<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'client_id',
        'name',
        'status',
        'value_cents',
        'due_date',
        'notes',
        'style_guide',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public const STATUSES = ['draft', 'active', 'review', 'completed', 'cancelled'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function publications()
    {
        return $this->belongsToMany(Publication::class, 'project_publication')
            ->withPivot('price_cents', 'status')
            ->withTimestamps();
    }

    public function documents()
    {
        return $this->hasMany(ProjectDocument::class);
    }

    public function messages()
    {
        return $this->hasMany(ProjectMessage::class)->latest();
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function getFormattedValueAttribute(): ?string
    {
        return $this->value_cents !== null
            ? '$' . number_format($this->value_cents / 100, 2)
            : null;
    }
}

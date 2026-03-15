<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'message',
        'source',
        'status',
        'notes',
    ];

    public const STATUSES = ['new', 'contacted', 'qualified', 'won', 'lost'];
}

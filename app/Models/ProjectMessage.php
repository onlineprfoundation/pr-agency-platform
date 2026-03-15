<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMessage extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'sender_type',
        'content',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isFromClient(): bool
    {
        return $this->sender_type === 'client';
    }
}

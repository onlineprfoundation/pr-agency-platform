<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'email',
        'company',
        'phone',
        'logo_path',
        'link',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicationOrderDocument extends Model
{
    protected $fillable = [
        'publication_order_id',
        'name',
        'file_path',
        'mime_type',
        'size',
    ];

    public function publicationOrder()
    {
        return $this->belongsTo(PublicationOrder::class);
    }
}

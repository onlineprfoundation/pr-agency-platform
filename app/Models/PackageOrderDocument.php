<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageOrderDocument extends Model
{
    protected $fillable = [
        'package_order_id',
        'name',
        'file_path',
        'mime_type',
        'size',
    ];

    public function packageOrder()
    {
        return $this->belongsTo(PackageOrder::class);
    }
}

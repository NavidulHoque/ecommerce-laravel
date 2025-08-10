<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceFiles extends Model
{
    protected $fillable = [
        'resource_id',
        'file_url',
        'file_type'
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        "created_by"
    ];

    public function subcategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

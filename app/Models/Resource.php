<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'discount_price',
        'quantity',
        'category_id',
        'sub_category_id',
        'created_by',
        'status',
        'product_id'
    ];

    public function resource_files()
    {
        return $this->hasMany(ResourceFiles::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function cart_items()
    {
        return $this->hasMany(CartItems::class);
    }

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

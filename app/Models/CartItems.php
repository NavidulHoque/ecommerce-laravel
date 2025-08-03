<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItems extends Model
{

    protected $fillable = [
        'cart_id',
        'resource_id',
        'quantity',
        'price'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}

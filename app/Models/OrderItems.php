<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $fillable = [
        'order_id',
        'resource_id',
        'quantity',
        'price',
        'status'
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

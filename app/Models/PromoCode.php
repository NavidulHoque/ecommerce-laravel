<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $fillable = [
        "code",
        'discount',
        'start_date',
        'expiry_date',
        'status',
        'created_by'
    ];

    // needed for converting date strings to date objects
    protected $casts = [
        'start_date'  => 'datetime',
        'expiry_date' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

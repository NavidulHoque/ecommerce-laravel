<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'role',
        'gender',
        'profile_image',
        'otp',
        'otp_expires',
        'refresh_token',
    ];

    protected $hidden = [
        'password',
        'otp',
        'otp_expires',
        'refresh_token',
    ];

    protected $casts = [
        'password' => 'hashed'
    ];

    // ✅ Relationships

    public function categories()
    {
        return $this->hasMany(Category::class, 'created_by');
    }

    public function resources()
    {
        return $this->hasMany(Resource::class, 'created_by');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }
}

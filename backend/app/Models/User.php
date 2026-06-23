<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'phone', 'country', 'region', 'avatar', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    // ─── Relationships ───────────────────────────────────────
    public function orders()   { return $this->hasMany(Order::class); }
    public function listings() { return $this->hasMany(Listing::class); }
    public function products() { return $this->hasMany(Product::class); }

    // ─── Role helpers ────────────────────────────────────────
    public function isAdmin()    { return $this->role === 'admin'; }
    public function isFarmer()   { return $this->role === 'farmer'; }
    public function isTrader()   { return $this->role === 'trader'; }
    public function isInvestor() { return $this->role === 'investor'; }
    public function isCustomer() { return $this->role === 'customer'; }
}
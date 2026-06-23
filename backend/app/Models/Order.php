<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'user_id', 'order_number', 'status', 'subtotal', 'shipping',
        'total', 'shipping_address', 'shipping_country', 'notes',
        'approved_at', 'shipped_at', 'delivered_at'
    ];
    
    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total' => 'decimal:2',
        'approved_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
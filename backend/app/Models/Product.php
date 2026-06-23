<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'category_id', 'user_id', 'name', 'name_am', 'description',
        'description_am', 'origin', 'grade', 'process', 'price_per_kg',
        'stock_kg', 'image', 'is_available', 'is_featured'
    ];
    
    protected $casts = [
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'price_per_kg' => 'decimal:2'
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
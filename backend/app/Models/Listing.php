<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Listing extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'user_id', 'title', 'description', 'origin', 'grade',
        'process', 'quantity_kg', 'asking_price_per_kg',
        'harvest_date', 'status', 'image'
    ];
    
    protected $casts = [
        'asking_price_per_kg' => 'decimal:2',
        'harvest_date' => 'date'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
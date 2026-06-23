<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'name_am', 'slug', 'description'
    ];
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvestmentOpportunity extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'title', 'description', 'type', 'location',
        'min_investment', 'expected_return_pct',
        'duration_months', 'status', 'image'
    ];
    
    protected $casts = [
        'min_investment' => 'decimal:2',
        'expected_return_pct' => 'decimal:2'
    ];
}
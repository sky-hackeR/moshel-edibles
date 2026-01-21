<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'name',
        'symbol',
        'unit_type',
        'base_unit',
        'is_active',
        'use_for_purchase',
        'use_for_recipe',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'use_for_purchase' => 'boolean',
        'use_for_recipe' => 'boolean',
    ];

    /*
     * Scopes (very useful for dropdowns)
     */

    public function scopeActive($query){
        return $query->where('is_active', true);
    }

    public function scopeForPurchase($query){
        return $query->where('use_for_purchase', true);
    }

    public function scopeForRecipe($query){
        return $query->where('use_for_recipe', true);
    }

    public function scopeBase($query){
        return $query->whereColumn('symbol', 'base_unit');
    }

}


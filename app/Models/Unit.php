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
        'base_multiplier', // Added: The engine field
        'base_unit',
        'is_active',
        'use_for_purchase',
        'use_for_recipe',
    ];

    protected $casts = [
        'base_multiplier'  => 'float',   
        'is_active'        => 'boolean',
        'use_for_purchase' => 'boolean',
        'use_for_recipe'   => 'boolean',
    ];

    /*
     * HELPER METHOD
     */
    public function toBase($quantity)
    {
        return (float) $quantity * (float) $this->base_multiplier;
    }

    /*
     * Scopes
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
        return $query->where('base_multiplier', 1.00);
    }
}
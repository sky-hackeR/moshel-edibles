<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ingredient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'base_unit_id',
        'reorder_level',
        'is_active',
    ];

    protected $casts = [
        'reorder_level' => 'decimal:3',
        'is_active' => 'boolean',
    ];

    public function getEffectiveReorderLevelAttribute()
    {
        if ($this->reorder_level !== null) {
            return (float) $this->reorder_level;
        }

        $thresholds = [
            'g' => 1000,
            'ml' => 1000,
            'pcs' => 10,
        ];

        $unitSymbol = strtolower($this->baseUnit->symbol ?? '');

        return $thresholds[$unitSymbol] ?? 0;
    }

    public function baseUnit()
    {
        return $this->belongsTo(Unit::class, 'base_unit_id');
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function recipeItems()
    {
        return $this->hasMany(RecipeItem::class);
    }
}

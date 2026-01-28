<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionItem extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'production_id',
        'ingredient_id',
        'quantity_used',
        'unit_cost',
        'total_cost',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}

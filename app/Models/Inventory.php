<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'ingredient_id',
        'quantity',
        'average_cost',
    ];

    protected $casts = [
        'quantity' => 'float',
        'average_cost' => 'float',
    ];

    /*
     * Relationships
     */

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}

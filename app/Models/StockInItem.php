<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockInItem extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'stock_in_id',
        'ingredient_id',
        'unit_id',
        'quantity',
        'base_quantity',
        'unit_price',
        'total_price',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}

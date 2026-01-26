<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'product_id',
        'note',
        'is_active'
    ];

    public function items()
    {
        return $this->hasMany(RecipeItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class OpeningStock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_type',
        'product_id',
        'ingredient_id',
        'quantity',
        'average_cost',
        'reason',
        'admin_id',
    ];

    protected $casts = [
        'quantity'     => 'float',
        'average_cost' => 'float',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
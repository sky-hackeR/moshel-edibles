<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'selling_price',
        'sales_unit',    
        'stock_on_hand',
    ];


    protected $casts = [
        'selling_price' => 'float',
        'is_active'     => 'boolean',
    ];

    public function recipe()
    {
        return $this->hasOne(Recipe::class);
    }

    public function productions()
    {
        return $this->hasMany(Production::class);
    }

    /**
     * Reduce stock when a sale occurs
     */
    public function reduceStock($quantity)
    {
        if ($this->stock_on_hand < $quantity) {
            throw new \Exception("Insufficient stock for {$this->name}.");
        }

        $this->decrement('stock_on_hand', $quantity);
    }

    /**
     * Increase stock (useful for returns or production)
     */
    public function addStock($quantity)
    {
        $this->increment('stock_on_hand', $quantity);
    }

    /**
     * Check if product is low on stock (Scalability: for future alerts)
     */
    public function isLowStock($threshold = 10)
    {
        return $this->stock_on_hand <= $threshold;
    }
}

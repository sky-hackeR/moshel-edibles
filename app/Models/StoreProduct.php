<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'store_title',
        'short_description',
        'description',
        'meta_title',
        'meta_description',
        'is_published',
        'is_featured',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * The ERP product represented by this storefront product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * All images belonging to this storefront product.
     */
    public function images()
    {
        return $this->hasMany(StoreProductImage::class)
            ->orderBy('sort_order');
    }

    /**
     * The primary storefront image.
     */
    public function primaryImage()
    {
        return $this->hasOne(StoreProductImage::class)
            ->where('is_primary', true);
    }
}
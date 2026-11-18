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
     * Accessor: Ensure short description is always clean plain text without HTML tags.
     */
    public function getShortDescriptionAttribute($value)
    {
        return $value !== null ? trim(html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8')) : null;
    }

    /**
     * Mutator: Strip all HTML tags before storing short description in the database.
     */
    public function setShortDescriptionAttribute($value)
    {
        $this->attributes['short_description'] = $value !== null ? trim(html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8')) : null;
    }

    /**
     * Accessor: Ensure meta description is always clean plain text for SEO.
     */
    public function getMetaDescriptionAttribute($value)
    {
        return $value !== null ? trim(html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8')) : null;
    }

    /**
     * Mutator: Strip all HTML tags before storing meta description in the database.
     */
    public function setMetaDescriptionAttribute($value)
    {
        $this->attributes['meta_description'] = $value !== null ? trim(html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8')) : null;
    }

    /**
     * Accessor: Ensure meta title is always clean plain text for SEO.
     */
    public function getMetaTitleAttribute($value)
    {
        return $value !== null ? trim(html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8')) : null;
    }

    /**
     * Mutator: Strip all HTML tags before storing meta title in the database.
     */
    public function setMetaTitleAttribute($value)
    {
        $this->attributes['meta_title'] = $value !== null ? trim(html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8')) : null;
    }

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
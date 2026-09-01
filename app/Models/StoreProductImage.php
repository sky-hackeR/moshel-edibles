<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreProductImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'store_product_id',
        'path',
        'alt_text',
        'sort_order',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * The storefront product this image belongs to.
     */
    public function storeProduct()
    {
        return $this->belongsTo(StoreProduct::class);
    }
}
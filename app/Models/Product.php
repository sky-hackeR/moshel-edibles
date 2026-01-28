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
}

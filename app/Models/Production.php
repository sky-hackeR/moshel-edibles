<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Production extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'quantity',
        'staff_id',
        'admin_id',
        'unit_cost',
        'total_cost',
        'selling_price',
        'expected_revenue',
        'profit',
        'notes',       
        'produced_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function items()
    {
        return $this->hasMany(ProductionItem::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    // Relationship to Admin
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'reference',
        'purchase_date',
        'supplier',
        'note',
        'created_by',
    ];

    public function items()
    {
        return $this->hasMany(StockInItem::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}

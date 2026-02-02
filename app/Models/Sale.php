<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference_no',
        'user_id',
        'user_type',
        'total_amount',
        'discount_amount',
        'payable_amount',
        'payment_method',
        'notes'
    ];

    // This makes the seller_name available in the JSON response automatically
    protected $appends = ['seller_name'];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function staff() {
        // We link user_id to the staff table's id
        return $this->belongsTo(Staff::class, 'user_id');
    }

    public function admin() {
        // We link user_id to the admin table's id
        return $this->belongsTo(Admin::class, 'user_id');
    }

    // This is the "seller_name" logic used in your controller
    public function getSellerNameAttribute() {
        if ($this->user_type === 'staff') {
            return $this->staff->name ?? 'Staff';
        }
        return $this->admin->name ?? 'Admin';
    }

}
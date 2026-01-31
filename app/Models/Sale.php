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

    /**
     * Logic to fetch the name of the Admin or Staff who made the sale
     */
    public function getSellerNameAttribute()
    {
        if ($this->user_type === 'admin') {
            $admin = \App\Models\Admin::find($this->user_id);
            return $admin ? $admin->name : 'Administrator';
        }
        
        $staff = \App\Models\Staff::find($this->user_id);
        return $staff ? $staff->name : 'Staff Member';
    }
}
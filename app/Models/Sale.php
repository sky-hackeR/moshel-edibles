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
        'notes',
        'customer_id',
        'order_status',
        'payment_status',
        'paystack_reference',
        'paystack_amount',
        'paystack_currency',
        'paid_at',
        'delivery_address',
        'delivery_phone',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'paystack_amount' => 'integer',
    ];

    // This makes the seller_name, channel, and in-store status available in JSON responses
    protected $appends = ['seller_name', 'channel', 'is_in_store'];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function staff() {
        return $this->belongsTo(Staff::class, 'user_id');
    }

    public function admin() {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Seller / Cashier / Channel identification
     */
    public function getSellerNameAttribute() {
        if ($this->user_type === 'staff') {
            return $this->staff->name ?? 'Staff Cashier';
        }
        if ($this->user_type === 'admin') {
            return $this->admin->name ?? 'Admin Cashier';
        }
        if ($this->customer) {
            return $this->customer->name . ' (Online)';
        }
        return 'Online Customer';
    }

    /**
     * Identifies whether sale was In-Store POS or Online Storefront
     */
    public function getChannelAttribute() {
        if ($this->user_type === 'staff' || $this->user_type === 'admin') {
            return 'In-Store (POS)';
        }
        return 'Online Store';
    }

    /**
     * Check if sale was performed in-store
     */
    public function getIsInStoreAttribute() {
        return in_array($this->user_type, ['staff', 'admin']);
    }

    /**
     * Scope for paid sales (both completed in-store POS and paid online orders)
     */
    public function scopePaid($query) {
        return $query->where(function ($q) {
            $q->where('payment_status', 'paid')
              ->orWhereIn('order_status', ['completed', 'delivered', 'processing'])
              ->orWhere(function ($sub) {
                  $sub->whereIn('user_type', ['staff', 'admin'])
                      ->whereNull('paystack_reference');
              });
        });
    }
}
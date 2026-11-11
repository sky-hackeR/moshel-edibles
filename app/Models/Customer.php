<?php

namespace App\Models;

use App\Notifications\CustomerResetPassword;
use App\Notifications\CustomerVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Customer extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($customer) {
            if (empty($customer->slug)) {
                $baseSlug = Str::slug($customer->name) ?: 'customer';
                $slug = $baseSlug;

                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . Str::lower(Str::random(8));
                }

                $customer->slug = $slug;
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'address', 'slug', 'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function getInitialsAttribute()
    {
        $parts = preg_split('/\s+/u', trim($this->name), -1, PREG_SPLIT_NO_EMPTY);
        if (empty($parts)) {
            return '';
        }

        $initials = mb_substr($parts[0], 0, 1, 'UTF-8');
        if (count($parts) > 1) {
            $initials .= mb_substr(end($parts), 0, 1, 'UTF-8');
        }

        return mb_strtoupper($initials, 'UTF-8');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomerResetPassword($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomerVerifyEmail());
    }
}

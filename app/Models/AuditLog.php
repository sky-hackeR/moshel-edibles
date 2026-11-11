<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected $casts = [
        'details' => 'array',
        'created_at' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(AuditSession::class, 'audit_session_id');
    }

    public function getCategoryBadgeClassAttribute()
    {
        return match (strtolower($this->category ?? '')) {
            'auth' => 'bg-info text-dark',
            'pos', 'sales' => 'bg-success text-white',
            'inventory', 'stock' => 'bg-warning text-dark',
            'production' => 'bg-primary text-white',
            'products', 'recipes' => 'bg-purple text-white',
            'orders', 'checkout' => 'bg-success text-white',
            'customers' => 'bg-info text-white',
            'system', 'settings' => 'bg-dark text-white',
            default => 'bg-secondary text-white',
        };
    }
}

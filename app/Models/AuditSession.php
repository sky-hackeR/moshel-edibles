<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuditSession extends Model
{
    use HasFactory;

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

    protected $dates = [
        'started_at',
        'last_activity_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'started_at' => 'datetime',
        'last_activity_at' => 'datetime',
    ];

    public function logs()
    {
        return $this->hasMany(AuditLog::class, 'audit_session_id');
    }

    public function getDurationAttribute()
    {
        if (!$this->started_at || !$this->last_activity_at) {
            return '—';
        }

        $minutes = $this->started_at->diffInMinutes($this->last_activity_at);
        if ($minutes < 1) {
            $seconds = $this->started_at->diffInSeconds($this->last_activity_at);
            return $seconds . 's';
        }
        if ($minutes < 60) {
            return $minutes . 'm';
        }
        $hours = floor($minutes / 60);
        $remMin = $minutes % 60;
        return "{$hours}h {$remMin}m";
    }

    public function getIsActuallyActiveAttribute(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        return $this->last_activity_at && $this->last_activity_at->gt(now()->subMinutes(30));
    }

    public function getRoleBadgeClassAttribute()
    {
        return match ($this->user_type) {
            'admin' => 'bg-danger text-white',
            'staff' => 'bg-primary text-white',
            'customer' => 'bg-success text-white',
            default => 'bg-secondary text-white',
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImportExportLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'admin_id',
        'operation',
        'module',
        'filename',
        'file_path',
        'ip_address',
        'user_agent',
        'status',
        'total_rows',
        'successful_rows',
        'failed_rows',
        'metadata',
        'remarks',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getSuccessRateAttribute()
    {
        if ($this->total_rows == 0) {
            return 0;
        }

        return round(($this->successful_rows / $this->total_rows) * 100, 2);
    }

    public function getDurationAttribute()
    {
        if (!$this->started_at || !$this->completed_at) {
            return null;
        }

        return $this->started_at->diffForHumans(
            $this->completed_at,
            true
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    public function isSuccessful()
    {
        return $this->status === 'success';
    }

    public function isPartial()
    {
        return $this->status === 'partial';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }
}
<?php

namespace App\Services\BulkOperations;

use App\Models\ImportExportLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BulkLogger
{
    /**
     * Start a new bulk operation.
     */
    public static function start(
        string $operation,
        string $module,
        string $filename,
        ?Request $request = null
    ): ImportExportLog {

        return ImportExportLog::create([

            'admin_id' => Auth::guard('admin')->id(),

            'operation' => strtolower($operation),

            'module' => strtolower($module),

            'filename' => $filename,

            'status' => 'processing',

            'started_at' => now(),

            'ip_address' => $request?->ip(),

            'user_agent' => $request?->userAgent(),

            'total_rows' => 0,

            'successful_rows' => 0,

            'failed_rows' => 0,

        ]);
    }

    /**
     * Update import progress.
     */
    public static function progress(
        ImportExportLog $log,
        int $successful,
        int $failed
    ): void {

        $log->update([

            'successful_rows' => $successful,

            'failed_rows' => $failed,

        ]);
    }

    /**
     * Save uploaded/generated file path.
     */
    public static function file(
        ImportExportLog $log,
        string $path
    ): void {

        $log->update([

            'file_path' => $path,

        ]);
    }

    /**
     * Finish successfully.
     */
    public static function success(
        ImportExportLog $log,
        int $totalRows,
        int $successfulRows,
        int $failedRows = 0,
        array $metadata = [],
        ?string $remarks = null
    ): void {

        $log->update([

            'status' => $failedRows > 0
                ? 'partial'
                : 'success',

            'total_rows' => $totalRows,

            'successful_rows' => $successfulRows,

            'failed_rows' => $failedRows,

            'metadata' => $metadata,

            'remarks' => $remarks,

            'completed_at' => now(),

        ]);
    }

    /**
     * Finish as failed.
     */
    public static function failed(
        ImportExportLog $log,
        string $message,
        array $metadata = []
    ): void {

        $log->update([

            'status' => 'failed',

            'remarks' => $message,

            'metadata' => $metadata,

            'completed_at' => now(),

        ]);
    }

    /**
     * Cancel operation.
     */
    public static function cancelled(
        ImportExportLog $log,
        ?string $remarks = null
    ): void {

        $log->update([

            'status' => 'cancelled',

            'remarks' => $remarks,

            'completed_at' => now(),

        ]);
    }

    /**
     * Store custom metadata.
     */
    public static function metadata(
        ImportExportLog $log,
        array $metadata
    ): void {

        $log->update([

            'metadata' => $metadata,

        ]);
    }

    /**
     * Append remarks.
     */
    public static function remark(
        ImportExportLog $log,
        string $remark
    ): void {

        $existing = $log->remarks;

        $log->update([

            'remarks' => trim(
                ($existing ? $existing.PHP_EOL : '') . $remark
            ),

        ]);
    }
}
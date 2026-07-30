<?php

namespace App\Imports;

use Throwable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;

abstract class BaseImport implements SkipsOnFailure, SkipsOnError
{
    use SkipsFailures;
    use SkipsErrors;

    /**
     * --------------------------------------------------------
     * Import Counters
     * --------------------------------------------------------
     */

    protected int $totalRows = 0;

    protected int $successfulRows = 0;

    protected int $failedRows = 0;

    protected int $skippedRows = 0;

    protected int $createdRows = 0;

    protected int $updatedRows = 0;

    /**
     * --------------------------------------------------------
     * Metadata
     * --------------------------------------------------------
     */

    protected array $metadata = [];

    protected array $warnings = [];

    protected array $errorMessages = [];

    /**
     * --------------------------------------------------------
     * Timing
     * --------------------------------------------------------
     */

    protected float $startedAt;

    public function __construct()
    {
        $this->startedAt = microtime(true);
    }

    /**
     * --------------------------------------------------------
     * Counters
     * --------------------------------------------------------
     */

    protected function rowStarted(): void
    {
        $this->totalRows++;
    }

    protected function rowImported(): void
    {
        $this->successfulRows++;
    }

    protected function rowFailed(): void
    {
        $this->failedRows++;
    }

    protected function rowSkipped(): void
    {
        $this->skippedRows++;
    }

    protected function rowCreated(): void
    {
        $this->createdRows++;
    }

    protected function rowUpdated(): void
    {
        $this->updatedRows++;
    }

    /**
     * --------------------------------------------------------
     * Error Handling
     * --------------------------------------------------------
     */

    public function onError(Throwable $e)
    {
        $this->rowFailed();

        $this->addError($e->getMessage());
    }

    /**
     * --------------------------------------------------------
     * Metadata Helpers
     * --------------------------------------------------------
     */

    public function addMetadata(
        string $key,
        $value
    ): void {

        $this->metadata[$key] = $value;

    }

    public function addWarning(
        string $message
    ): void {

        $this->warnings[] = $message;

    }

    public function addError(
        string $message
    ): void {

        $this->errorMessages[] = $message;

    }

    /**
     * --------------------------------------------------------
     * Utility Helpers
     * --------------------------------------------------------
     */

    protected function isEmptyRow(array $row): bool
    {
        foreach ($row as $value) {

            if (!is_null($value) && trim((string) $value) !== '') {

                return false;

            }

        }

        return true;
    }

    /**
     * --------------------------------------------------------
     * Import Summary
     * --------------------------------------------------------
     */

    public function summary(): array
    {
        return [

            'total' => $this->totalRows,

            'successful' => $this->successfulRows,

            'failed' => $this->failedRows,

            'skipped' => $this->skippedRows,

            'created' => $this->createdRows,

            'updated' => $this->updatedRows,

            'success_rate' => $this->totalRows > 0
                ? round(($this->successfulRows / $this->totalRows) * 100, 2)
                : 0,

            'duration' => round(
                microtime(true) - $this->startedAt,
                2
            ),

            'metadata' => $this->metadata,

            'warnings' => $this->warnings,

            'errors' => $this->errorMessages,

            'validation_failures' => count($this->failures()),

            'exceptions' => count($this->errors()),

        ];
    }
}
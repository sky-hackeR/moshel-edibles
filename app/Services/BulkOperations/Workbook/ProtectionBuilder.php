<?php

namespace App\Services\BulkOperations\Workbook;

use App\Services\BulkOperations\Workbook\Helpers\ColumnHelper;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProtectionBuilder
{
    /**
     * ---------------------------------------------------------
     * Apply Protection
     * ---------------------------------------------------------
     */
    public function build(
        Worksheet $sheet,
        WorksheetDefinition $definition
    ): void {

        if (! $definition->shouldProtect()) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Unlock Everything
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle(
            $sheet->calculateWorksheetDimension()
        )->getProtection()->setLocked(
            Protection::PROTECTION_UNPROTECTED
        );

        /*
        |--------------------------------------------------------------------------
        | Lock Header
        |--------------------------------------------------------------------------
        */

        $highestColumn = $sheet->getHighestColumn();

        $sheet->getStyle(
            "A1:{$highestColumn}1"
        )->getProtection()->setLocked(
            Protection::PROTECTION_PROTECTED
        );

        /*
        |--------------------------------------------------------------------------
        | Lock Non-editable Columns
        |--------------------------------------------------------------------------
        */

        foreach ($definition->getColumns() as $index => $column) {

            if ($column->isEditable()) {
                continue;
            }

            $range = ColumnHelper::dataRange($index + 1);

            $sheet->getStyle($range)
                ->getProtection()
                ->setLocked(
                    Protection::PROTECTION_PROTECTED
                );

        }

        /*
        |--------------------------------------------------------------------------
        | Enable Protection
        |--------------------------------------------------------------------------
        */

        $protection = $sheet->getProtection();

        $protection->setSheet(true);

        $protection->setSort(true);

        $protection->setInsertRows(true);

        $protection->setAutoFilter(true);

        $protection->setFormatCells(false);

        $protection->setDeleteRows(false);

        $protection->setInsertColumns(false);

        $protection->setDeleteColumns(false);

    }

    /**
     * ---------------------------------------------------------
     * Lock Arbitrary Range
     * ---------------------------------------------------------
     */
    public function lockRange(
        Worksheet $sheet,
        string $range
    ): void {

        $sheet->getStyle($range)
            ->getProtection()
            ->setLocked(
                Protection::PROTECTION_PROTECTED
            );

    }

    /**
     * ---------------------------------------------------------
     * Unlock Arbitrary Range
     * ---------------------------------------------------------
     */
    public function unlockRange(
        Worksheet $sheet,
        string $range
    ): void {

        $sheet->getStyle($range)
            ->getProtection()
            ->setLocked(
                Protection::PROTECTION_UNPROTECTED
            );

    }
}
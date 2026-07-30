<?php

namespace App\Services\BulkOperations\Workbook;

use App\Services\BulkOperations\Workbook\Helpers\ColumnHelper;
use PhpOffice\PhpSpreadsheet\NamedRange;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LookupSheetBuilder
{
    /**
     * Hidden worksheet.
     */
    protected string $sheetName = '_lookups';

    /**
     * Build lookup worksheet.
     */
    public function build(
        Spreadsheet $spreadsheet,
        WorksheetDefinition $definition
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Does anything need lookups?
        |--------------------------------------------------------------------------
        */

        if (! $this->hasLookupColumns($definition)) {
            return;
        }

        $sheet = $this->lookupSheet($spreadsheet);

        $lookupColumn = 1;

        foreach ($definition->getColumns() as $column) {

            if (! $column->hasDropdown()) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Small lists don't need lookup sheets.
            |--------------------------------------------------------------------------
            */

            if (strlen(implode(',', $column->getDropdown())) <= 255) {
                continue;
            }

            $letter = ColumnHelper::letter($lookupColumn);

            foreach ($column->getDropdown() as $row => $value) {

                $sheet->setCellValue(
                    "{$letter}" . ($row + 1),
                    $value
                );

            }

            $rangeName = preg_replace(
                '/[^A-Za-z0-9_]/',
                '',
                $column->getTitle()
            );

            $spreadsheet->addNamedRange(

                new NamedRange(

                    $rangeName,

                    $sheet,

                    '$' .
                    $letter .
                    '$1:$' .
                    $letter .
                    '$' .
                    count($column->getDropdown())

                )

            );

            /*
            |--------------------------------------------------------------------------
            | Store lookup name back on the column.
            |--------------------------------------------------------------------------
            */

            $column->lookup($rangeName);

            $lookupColumn++;

        }

    }

    /**
     * ---------------------------------------------------------
     * Get/Create Lookup Sheet
     * ---------------------------------------------------------
     */
    protected function lookupSheet(
        Spreadsheet $spreadsheet
    ): Worksheet {

        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {

            if ($worksheet->getTitle() === $this->sheetName) {

                return $worksheet;

            }

        }

        $sheet = new Worksheet(
            $spreadsheet,
            $this->sheetName
        );

        $sheet->setSheetState(
            Worksheet::SHEETSTATE_HIDDEN
        );

        $spreadsheet->addSheet($sheet);

        return $sheet;

    }

    /**
     * ---------------------------------------------------------
     * Does workbook require lookup sheet?
     * ---------------------------------------------------------
     */
    protected function hasLookupColumns(
        WorksheetDefinition $definition
    ): bool {

        foreach ($definition->getColumns() as $column) {

            if (
                $column->hasDropdown() &&
                strlen(implode(',', $column->getDropdown())) > 255
            ) {
                return true;
            }

        }

        return false;

    }
}
<?php

namespace App\Services\BulkOperations\Workbook;

use App\Services\BulkOperations\Workbook\Helpers\ColumnHelper;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FormulaBuilder
{
    /**
     * ---------------------------------------------------------
     * Apply Worksheet Formulas
     * ---------------------------------------------------------
     */
    public function build(
        Worksheet $sheet,
        WorksheetDefinition $definition
    ): void {

        foreach ($definition->getColumns() as $index => $column) {

            /*
            |--------------------------------------------------------------------------
            | Skip Columns Without Formula
            |--------------------------------------------------------------------------
            */

            if (! $column->hasFormula()) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Excel Column Letter
            |--------------------------------------------------------------------------
            */

            $letter = ColumnHelper::letter($index + 1);

            /*
            |--------------------------------------------------------------------------
            | Apply Formula Down The Column
            |--------------------------------------------------------------------------
            */

            for (
                $row = $column->getFormulaStartRow();
                $row <= $column->getFormulaEndRow();
                $row++
            ) {

                $sheet->setCellValue(

                    "{$letter}{$row}",

                    str_replace(
                        '{row}',
                        $row,
                        $column->getFormula()
                    )

                );

            }

        }

    }
}
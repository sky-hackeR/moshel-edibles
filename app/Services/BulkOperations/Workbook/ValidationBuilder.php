<?php

namespace App\Services\BulkOperations\Workbook;

use App\Services\BulkOperations\Workbook\Helpers\ColumnHelper;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ValidationBuilder
{
    /**
     * ---------------------------------------------------------
     * Apply Validation
     * ---------------------------------------------------------
     */
    public function build(
        Worksheet $sheet,
        WorksheetDefinition $definition
    ): void {

        foreach ($definition->getColumns() as $index => $column) {

            if (! $column->hasDropdown()) {
                continue;
            }

            $this->applyDropdown(

                $sheet,

                ColumnHelper::letter($index + 1),

                $column,

            );

        }

    }

    /**
     * ---------------------------------------------------------
     * Apply Dropdown Validation
     * ---------------------------------------------------------
     */
    protected function applyDropdown(
        Worksheet $sheet,
        string $letter,
        ColumnDefinition $column
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Lookup Sheet
        |--------------------------------------------------------------------------
        */

        if ($column->getLookup()) {

            $formula = '=' . $column->getLookup();

        } else {

            $formula = '"' . implode(',', $column->getDropdown()) . '"';

        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        for ($row = 2; $row <= 1000; $row++) {

            $validation = $sheet
                ->getCell("{$letter}{$row}")
                ->getDataValidation();

            $validation->setType(
                DataValidation::TYPE_LIST
            );

            $validation->setErrorStyle(
                DataValidation::STYLE_STOP
            );

            $validation->setAllowBlank(
                ! $column->isRequired()
            );

            $validation->setShowInputMessage(true);

            $validation->setShowErrorMessage(true);

            $validation->setShowDropDown(true);

            $validation->setFormula1($formula);

            $validation->setPromptTitle(
                'Select a value'
            );

            $validation->setPrompt(
                $column->getInstruction()
                ?? 'Select a value from the list.'
            );

            $validation->setErrorTitle(
                'Invalid value'
            );

            $validation->setError(
                'Please choose a value from the dropdown list.'
            );

        }

    }
}
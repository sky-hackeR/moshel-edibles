<?php

namespace App\Services\BulkOperations\Workbook;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WorkbookBuilder
{
    public function __construct(
        protected StyleBuilder $styles,
        protected ValidationBuilder $validation,
        protected CommentBuilder $comments,
        protected ProtectionBuilder $protection,
        protected LookupSheetBuilder $lookups,
        protected FormulaBuilder $formulas
    ) {
    }

    /**
     * --------------------------------------------------------
     * Build Workbook
     * --------------------------------------------------------
     */
    public function build(
        Spreadsheet $spreadsheet,
        Worksheet $sheet,
        WorksheetDefinition $definition
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Lookup Sheet
        |--------------------------------------------------------------------------
        |
        | Must exist BEFORE validations
        |
        */

        $this->lookups->build(

            $spreadsheet,

            $definition

        );

        /*
        |--------------------------------------------------------------------------
        | Styles
        |--------------------------------------------------------------------------
        */

        $this->styles->build(

            $sheet,

            $definition

        );

        /*
        |--------------------------------------------------------------------------
        | Comments
        |--------------------------------------------------------------------------
        */

        $this->comments->build(

            $sheet,

            $definition

        );

        /*
        |--------------------------------------------------------------------------
        | Formulas
        |--------------------------------------------------------------------------
        */

        $this->formulas->build(

            $sheet,

            $definition

        );

        /*
        |--------------------------------------------------------------------------
        | Data Validation
        |--------------------------------------------------------------------------
        */

        $this->validation->build(

            $sheet,

            $definition

        );

        /*
        |--------------------------------------------------------------------------
        | Worksheet Protection
        |--------------------------------------------------------------------------
        |
        | Must be LAST.
        |
        */

        $this->protection->build(

            $sheet,

            $definition

        );

    }
}
<?php

namespace App\Exports;

use App\Services\BulkOperations\Workbook\ColumnDefinition;
use App\Services\BulkOperations\Workbook\WorkbookBuilder;
use App\Services\BulkOperations\Workbook\WorksheetDefinition;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

use Maatwebsite\Excel\Events\AfterSheet;

abstract class BaseTemplateExport implements
    FromArray,
    WithHeadings,
    WithTitle,
    WithEvents
{
    use Exportable;

    /**
     * ---------------------------------------------------------
     * Cached Worksheet Definition
     * ---------------------------------------------------------
     */
    protected ?WorksheetDefinition $worksheet = null;

    /**
     * ---------------------------------------------------------
     * Worksheet Definition
     * ---------------------------------------------------------
     */
    abstract protected function definition(): WorksheetDefinition;

    /**
     * ---------------------------------------------------------
     * Cached Definition
     * ---------------------------------------------------------
     */
    protected function worksheet(): WorksheetDefinition
    {
        return $this->worksheet
            ??= $this->definition();
    }

    /**
     * ---------------------------------------------------------
     * Worksheet Title
     * ---------------------------------------------------------
     */
    public function title(): string
    {
        return $this->worksheet()->getTitle();
    }

    /**
     * ---------------------------------------------------------
     * Column Headings
     * ---------------------------------------------------------
     */
    public function headings(): array
    {
        return array_map(

            fn (ColumnDefinition $column) => $column->getTitle(),

            $this->worksheet()->getColumns()

        );
    }

    /**
     * ---------------------------------------------------------
     * Sample Rows
     * ---------------------------------------------------------
     */
    public function array(): array
    {
        return $this->worksheet()->getSamples();
    }

    /**
     * ---------------------------------------------------------
     * Workbook Events
     * ---------------------------------------------------------
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $spreadsheet = $sheet->getParent();

                app(WorkbookBuilder::class)
                    ->build(

                        $spreadsheet,

                        $sheet,

                        $this->worksheet()

                    );

            },

        ];
    }
}
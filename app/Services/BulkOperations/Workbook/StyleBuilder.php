<?php

namespace App\Services\BulkOperations\Workbook;

use App\Services\BulkOperations\Workbook\Helpers\ColumnHelper;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StyleBuilder
{
    public function build(
        Worksheet $sheet,
        WorksheetDefinition $definition
    ): void {

        $this->defaultFont($sheet);

        $this->header($sheet);

        $this->requiredColumns(
            $sheet,
            $definition
        );

        $this->alternateRows($sheet);

        $this->columnSettings(
            $sheet,
            $definition
        );
    }

    protected function defaultFont(
        Worksheet $sheet
    ): void {

        $sheet->getParent()
            ->getDefaultStyle()
            ->getFont()
            ->setName(Theme::FONT_FAMILY)
            ->setSize(Theme::FONT_SIZE);

    }

    protected function header(
        Worksheet $sheet
    ): void {

        $highestColumn = $sheet->getHighestColumn();

        $range = "A1:{$highestColumn}1";

        $sheet->getStyle($range)->applyFromArray([

            'font' => [

                'bold' => true,

                'size' => Theme::HEADER_FONT_SIZE,

                'color' => [
                    'rgb' => Theme::HEADER_TEXT,
                ],

            ],

            'fill' => [

                'fillType' => Fill::FILL_SOLID,

                'startColor' => [
                    'rgb' => Theme::HEADER_BACKGROUND,
                ],

            ],

            'alignment' => [

                'horizontal' => Alignment::HORIZONTAL_CENTER,

                'vertical' => Alignment::VERTICAL_CENTER,

            ],

            'borders' => [

                'allBorders' => [

                    'borderStyle' => Border::BORDER_THIN,

                    'color' => [
                        'rgb' => Theme::BORDER,
                    ],

                ],

            ],

        ]);

        $sheet
            ->getRowDimension(1)
            ->setRowHeight(
                Theme::HEADER_ROW_HEIGHT
            );
    }

    protected function requiredColumns(
        Worksheet $sheet,
        WorksheetDefinition $definition
    ): void {

        foreach ($definition->getColumns() as $index => $column) {

            if (! $column->isRequired()) {
                continue;
            }

            $cell = ColumnHelper::headerCell(
                $index + 1
            );

            $sheet
                ->getStyle($cell)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB(
                    Theme::REQUIRED_BACKGROUND
                );

        }
    }

    protected function alternateRows(
        Worksheet $sheet
    ): void {

        $highestRow = max(
            100,
            $sheet->getHighestRow()
        );

        $highestColumn = $sheet->getHighestColumn();

        for ($row = 2; $row <= $highestRow; $row++) {

            $color = $row % 2 === 0

                ? Theme::TABLE_ROW_EVEN

                : Theme::TABLE_ROW_ODD;

            $sheet
                ->getStyle(
                    "A{$row}:{$highestColumn}{$row}"
                )
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB($color);

        }

    }

    protected function columnSettings(
        Worksheet $sheet,
        WorksheetDefinition $definition
    ): void {

        foreach ($definition->getColumns() as $index => $column) {

            ColumnHelper::applyWidth(
                $sheet,
                $column,
                $index + 1
            );

            ColumnHelper::applyVisibility(
                $sheet,
                $column,
                $index + 1
            );

            ColumnHelper::applyFormat(
                $sheet,
                $column,
                $index + 1
            );

        }

    }
}
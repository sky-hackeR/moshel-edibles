<?php

namespace App\Services\BulkOperations\Workbook\Helpers;

use App\Services\BulkOperations\Workbook\ColumnDefinition;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ColumnHelper
{
    /**
     * ---------------------------------------------------------
     * Excel Column Letter
     * ---------------------------------------------------------
     */
    public static function letter(int $index): string
    {
        return CellHelper::letter($index);
    }

    /**
     * ---------------------------------------------------------
     * Header Cell
     * ---------------------------------------------------------
     */
    public static function headerCell(
        int $position
    ): string {

        return self::letter($position) . '1';

    }

    /**
     * ---------------------------------------------------------
     * Data Range
     * ---------------------------------------------------------
     */
    public static function dataRange(
        int $position,
        int $startRow = 2,
        int $endRow = 1000
    ): string {

        $letter = self::letter($position);

        return "{$letter}{$startRow}:{$letter}{$endRow}";

    }

    /**
     * ---------------------------------------------------------
     * Apply Width
     * ---------------------------------------------------------
     */
    public static function applyWidth(
        Worksheet $sheet,
        ColumnDefinition $column,
        int $position
    ): void {

        if ($column->getWidth() === null) {
            return;
        }

        $sheet
            ->getColumnDimension(
                self::letter($position)
            )
            ->setWidth(
                $column->getWidth()
            );

    }

    /**
     * ---------------------------------------------------------
     * Apply Visibility
     * ---------------------------------------------------------
     */
    public static function applyVisibility(
        Worksheet $sheet,
        ColumnDefinition $column,
        int $position
    ): void {

        $sheet
            ->getColumnDimension(
                self::letter($position)
            )
            ->setVisible(
                ! $column->isHidden()
            );

    }

    /**
     * ---------------------------------------------------------
     * Apply Auto Size
     * ---------------------------------------------------------
     */
    public static function applyAutoSize(
        Worksheet $sheet,
        int $position
    ): void {

        $sheet
            ->getColumnDimension(
                self::letter($position)
            )
            ->setAutoSize(true);

    }

    /**
     * ---------------------------------------------------------
     * Apply Number Format
     * ---------------------------------------------------------
     */
    public static function applyFormat(
        Worksheet $sheet,
        ColumnDefinition $column,
        int $position
    ): void {

        if ($column->getFormat() === null) {
            return;
        }

        $sheet
            ->getStyle(
                self::dataRange($position)
            )
            ->getNumberFormat()
            ->setFormatCode(
                $column->getFormat()
            );

    }
}
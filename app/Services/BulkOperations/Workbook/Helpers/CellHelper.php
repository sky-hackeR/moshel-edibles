<?php

namespace App\Services\BulkOperations\Workbook\Helpers;

class CellHelper
{
    /**
     * Convert column index to Excel letter.
     *
     * 1 => A
     * 2 => B
     * 27 => AA
     */
    public static function letter(int $index): string
    {
        $letter = '';

        while ($index > 0) {

            $index--;

            $letter = chr(65 + ($index % 26)) . $letter;

            $index = intdiv($index, 26);

        }

        return $letter;
    }

    /**
     * Convert Excel letter to index.
     *
     * A => 1
     * Z => 26
     * AA => 27
     */
    public static function index(string $letter): int
    {
        $letter = strtoupper($letter);

        $index = 0;

        foreach (str_split($letter) as $char) {

            $index *= 26;

            $index += ord($char) - 64;

        }

        return $index;
    }

    /**
     * Create cell reference.
     */
    public static function cell(
        string $column,
        int $row
    ): string {

        return $column . $row;

    }

    /**
     * Entire column.
     */
    public static function range(string $column): string
    {
        return "{$column}:{$column}";
    }

    /**
     * Column row range.
     */
    public static function rows(
        string $column,
        int $start,
        int $end
    ): string {

        return "{$column}{$start}:{$column}{$end}";

    }

    /**
     * Is a cell reference valid?
     */
    public static function valid(string $cell): bool
    {
        return (bool) preg_match(

            '/^[A-Z]{1,3}[0-9]+$/',

            strtoupper($cell)

        );
    }
}
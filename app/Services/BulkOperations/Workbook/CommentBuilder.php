<?php

namespace App\Services\BulkOperations\Workbook;

use App\Services\BulkOperations\Workbook\Helpers\ColumnHelper;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CommentBuilder
{
    /**
     * ---------------------------------------------------------
     * Apply Comments
     * ---------------------------------------------------------
     */
    public function build(
        Worksheet $sheet,
        WorksheetDefinition $definition
    ): void {

        foreach ($definition->getColumns() as $index => $column) {

            if ($column->getComment() === null) {
                continue;
            }

            $this->createComment(

                $sheet,

                ColumnHelper::headerCell($index + 1),

                $column->getComment()

            );

        }

    }

    /**
     * ---------------------------------------------------------
     * Create Comment
     * ---------------------------------------------------------
     */
    protected function createComment(
        Worksheet $sheet,
        string $cell,
        string $text
    ): void {

        $comment = $sheet->getComment($cell);

        $rich = new RichText();

        $title = $rich->createTextRun('Instructions');

        $title->getFont()->setBold(true);

        $rich->createText("\n\n");

        $rich->createText($text);

        $comment->setText($rich);

        $comment->setAuthor(config('app.name'));

        $comment->setWidth('250px');

        $comment->setHeight('120px');

        $comment->setMarginLeft('15px');

        $comment->setMarginTop('10px');

    }
}
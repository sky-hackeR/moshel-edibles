<?php

namespace App\Exports;

use App\Services\BulkOperations\Workbook\ColumnDefinition;
use App\Services\BulkOperations\Workbook\Theme;
use App\Services\BulkOperations\Workbook\WorksheetDefinition;

class ProductTemplateExport extends BaseTemplateExport
{
    /**
     * ---------------------------------------------------------
     * Worksheet Definition
     * ---------------------------------------------------------
     */
    protected function definition(): WorksheetDefinition
    {
        return WorksheetDefinition::make('Products')

            ->subtitle(
                'Use this template to import products into the inventory system.'
            )

            ->theme(
                Theme::PRIMARY
            )

            ->columns([

                ColumnDefinition::make('Name')
                    ->required()
                    ->width(35)
                    ->example('Fried Rice')
                    ->comment(
                        'Enter the product name. Example: Fried Rice.'
                    )
                    ->instruction(
                        'Each product name must be unique.'
                    ),

                ColumnDefinition::make('Sales Unit')
                    ->required()
                    ->width(20)
                    ->example('Plate')
                    ->dropdown([
                        'Plate',
                        'Pack',
                        'Bottle',
                        'Cup',
                        'Piece',
                        'Bowl',
                        'Portion',
                        'Tray',
                    ])
                    ->comment(
                        'Select how this product is sold.'
                    )
                    ->instruction(
                        'Choose one of the available sales units.'
                    ),

                ColumnDefinition::make('Selling Price')
                    ->required()
                    ->width(18)
                    ->format('#,##0.00')
                    ->example(3500)
                    ->comment(
                        'Selling price per sales unit.'
                    )
                    ->instruction(
                        'Enter numbers only. Do not include commas or currency symbols.'
                    ),

                ColumnDefinition::make('Is Active')
                    ->width(15)
                    ->example('Yes')
                    ->dropdown([
                        'Yes',
                        'No',
                    ])
                    ->comment(
                        'Choose whether this product is active.'
                    )
                    ->instruction(
                        'Yes = Active, No = Inactive.'
                    ),

            ])

            ->samples([

                [
                    'Fried Rice',
                    'Plate',
                    3500,
                    'Yes',
                ],

            ])

            ->freezeHeader()

            ->autoFilter()

            ->protect(false)

            ->hideLookups();
    }
}
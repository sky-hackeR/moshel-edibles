<?php

namespace App\Exports;

use App\Models\Ingredient;
use App\Models\Unit;
use App\Services\BulkOperations\Workbook\ColumnDefinition;
use App\Services\BulkOperations\Workbook\Theme;
use App\Services\BulkOperations\Workbook\WorksheetDefinition;

class StockTemplateExport extends BaseTemplateExport
{
    /**
     * ---------------------------------------------------------
     * Worksheet Definition
     * ---------------------------------------------------------
     */
    protected function definition(): WorksheetDefinition
    {
        return WorksheetDefinition::make('Stock In')

            ->subtitle(
                'Use this template to import stock purchases into the inventory system.'
            )

            ->theme(
                Theme::INFO
            )

            ->columns([

                ColumnDefinition::make('Purchase Date')
                    ->required()
                    ->width(18)
                    ->example('2026-07-30')
                    ->comment(
                        'Enter the purchase date using YYYY-MM-DD format.'
                    )
                    ->instruction(
                        'Example: 2026-07-30.'
                    ),

                ColumnDefinition::make('Supplier')
                    ->width(30)
                    ->example('Fresh Foods Ltd')
                    ->comment(
                        'Supplier name (optional).'
                    )
                    ->instruction(
                        'Optional field.'
                    ),

                ColumnDefinition::make('Ingredient')
                    ->required()
                    ->width(30)
                    ->example('Rice')
                    ->dropdown(
                        Ingredient::query()
                            ->where('is_active', true)
                            ->orderBy('name')
                            ->pluck('name')
                            ->toArray()
                    )
                    ->comment(
                        'Choose an ingredient from the dropdown.'
                    )
                    ->instruction(
                        'Only active ingredients can be selected.'
                    ),

                ColumnDefinition::make('Quantity')
                    ->required()
                    ->width(15)
                    ->format('#,##0.000')
                    ->example(50)
                    ->comment(
                        'Purchased quantity.'
                    )
                    ->instruction(
                        'Quantity must be greater than zero.'
                    ),

                ColumnDefinition::make('Unit')
                    ->required()
                    ->width(20)
                    ->example('Kilogram')
                    ->dropdown(
                        Unit::query()
                            ->where('is_active', true)
                            ->orderBy('name')
                            ->pluck('name')
                            ->toArray()
                    )
                    ->comment(
                        'Select the purchase unit.'
                    )
                    ->instruction(
                        'Choose a valid unit.'
                    ),

                ColumnDefinition::make('Unit Price')
                    ->required()
                    ->width(18)
                    ->format('#,##0.00')
                    ->example(52000)
                    ->comment(
                        'Cost per selected unit.'
                    )
                    ->instruction(
                        'Enter numbers only. Do not include commas or currency symbols.'
                    ),

                ColumnDefinition::make('Note')
                    ->width(40)
                    ->example('Weekly purchase')
                    ->comment(
                        'Optional purchase notes.'
                    )
                    ->instruction(
                        'Optional field.'
                    ),

            ])

            ->samples([

                [
                    '2026-07-30',
                    'Fresh Foods Ltd',
                    'Rice',
                    50,
                    'Kilogram',
                    52000,
                    'Weekly purchase',
                ],

            ])

            ->freezeHeader()

            ->autoFilter()

            ->protect(false)

            ->hideLookups();
    }
}
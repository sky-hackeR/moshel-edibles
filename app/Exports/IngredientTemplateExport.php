<?php

namespace App\Exports;

use App\Models\Unit;
use App\Services\BulkOperations\Workbook\ColumnDefinition;
use App\Services\BulkOperations\Workbook\Theme;
use App\Services\BulkOperations\Workbook\WorksheetDefinition;

class IngredientTemplateExport extends BaseTemplateExport
{
    /**
     * ---------------------------------------------------------
     * Worksheet Definition
     * ---------------------------------------------------------
     */
    protected function definition(): WorksheetDefinition
    {
        return WorksheetDefinition::make('Ingredients')

            ->subtitle(
                'Use this template to import ingredients into the inventory system.'
            )

            ->theme(
                Theme::SUCCESS
            )

            ->columns([

                ColumnDefinition::make('Name')
                    ->required()
                    ->width(35)
                    ->example('Sugar')
                    ->comment(
                        'Enter the ingredient name. Example: Sugar.'
                    )
                    ->instruction(
                        'Ingredient names must be unique.'
                    ),

                ColumnDefinition::make('Base Unit')
                    ->required()
                    ->width(25)
                    ->example('Kilogram')
                    ->dropdown(
                        Unit::query()
                            ->where('is_active', true)
                            ->where('base_multiplier', 1)
                            ->orderBy('unit_type')
                            ->pluck('name')
                            ->toArray()
                    )
                    ->comment(
                        'Choose the ingredient base unit from the dropdown.'
                    )
                    ->instruction(
                        'Only existing measurement units are allowed.'
                    ),

                ColumnDefinition::make('Is Active')
                    ->width(15)
                    ->example('Yes')
                    ->dropdown([
                        'Yes',
                        'No',
                    ])
                    ->comment(
                        'Choose whether the ingredient should be active.'
                    )
                    ->instruction(
                        'Yes = Active, No = Inactive.'
                    ),

            ])

            ->samples([

                [
                    'Sugar',
                    'Kilogram',
                    'Yes',
                ],

            ])

            ->freezeHeader()

            ->autoFilter()

            ->protect(false)

            ->hideLookups();
    }
}
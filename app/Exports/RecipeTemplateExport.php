<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Unit;
use App\Services\BulkOperations\Workbook\ColumnDefinition;
use App\Services\BulkOperations\Workbook\Theme;
use App\Services\BulkOperations\Workbook\WorksheetDefinition;

class RecipeTemplateExport extends BaseTemplateExport
{
    /**
     * ---------------------------------------------------------
     * Worksheet Definition
     * ---------------------------------------------------------
     */
    protected function definition(): WorksheetDefinition
    {
        return WorksheetDefinition::make('Recipes')

            ->subtitle(
                'Use this template to import recipes into the inventory system.'
            )

            ->theme(
                Theme::WARNING
            )

            ->columns([

                ColumnDefinition::make('Recipe')
                    ->required()
                    ->width(30)
                    ->example('Fried Rice')
                    ->comment(
                        'Recipe name. Multiple rows can share the same recipe name.'
                    )
                    ->instruction(
                        'Rows with the same recipe name will be grouped into one recipe during import.'
                    ),

                ColumnDefinition::make('Product')
                    ->required()
                    ->width(30)
                    ->example('Fried Rice')
                    ->dropdown(
                        Product::query()
                            ->where('is_active', true)
                            ->orderBy('name')
                            ->pluck('name')
                            ->toArray()
                    )
                    ->comment(
                        'Select the product this recipe belongs to.'
                    )
                    ->instruction(
                        'Only active products can be selected.'
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
                        'Choose an ingredient used in the recipe.'
                    )
                    ->instruction(
                        'Only active ingredients can be selected.'
                    ),

                ColumnDefinition::make('Quantity')
                    ->required()
                    ->width(15)
                    ->format('#,##0.000')
                    ->example(500)
                    ->comment(
                        'Enter the quantity required.'
                    )
                    ->instruction(
                        'Quantity must be greater than zero.'
                    ),

                ColumnDefinition::make('Unit')
                    ->required()
                    ->width(20)
                    ->example('Gram')
                    ->dropdown(
                        Unit::query()
                            ->where('is_active', true)
                            ->where('base_multiplier', 1)
                            ->orderBy('unit_type')
                            ->pluck('name')
                            ->toArray()
                    )
                    ->comment(
                        'Choose the measurement unit.'
                    )
                    ->instruction(
                        'Choose a valid measurement unit.'
                    ),

                ColumnDefinition::make('Is Active')
                    ->width(15)
                    ->example('Yes')
                    ->dropdown([
                        'Yes',
                        'No',
                    ])
                    ->comment(
                        'Set whether this recipe is active.'
                    )
                    ->instruction(
                        'Yes = Active, No = Inactive.'
                    ),

                ColumnDefinition::make('Note')
                    ->width(40)
                    ->example('Standard recipe')
                    ->comment(
                        'Optional notes or preparation instructions.'
                    )
                    ->instruction(
                        'Optional field.'
                    ),

            ])

            ->samples([

                [
                    'Fried Rice',
                    'Fried Rice',
                    'Rice',
                    500,
                    'Gram',
                    'Yes',
                    'Standard recipe',
                ],

            ])

            ->freezeHeader()

            ->autoFilter()

            ->protect(false)

            ->hideLookups();
    }
}
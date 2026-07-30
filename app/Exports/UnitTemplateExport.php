<?php

namespace App\Exports;

use App\Models\Unit;
use App\Services\BulkOperations\Workbook\ColumnDefinition;
use App\Services\BulkOperations\Workbook\Theme;
use App\Services\BulkOperations\Workbook\WorksheetDefinition;

class UnitTemplateExport extends BaseTemplateExport
{
    /**
     * ---------------------------------------------------------
     * Worksheet Definition
     * ---------------------------------------------------------
     */
    protected function definition(): WorksheetDefinition
    {
        return WorksheetDefinition::make('Units')

            ->subtitle(
                'Use this template to import measurement units into the inventory system.'
            )

            ->theme(
                Theme::SECONDARY
            )

            ->columns([

                ColumnDefinition::make('Name')
                    ->required()
                    ->width(28)
                    ->example('Kilogram')
                    ->comment(
                        'Display name of the unit. Example: Kilogram.'
                    )
                    ->instruction(
                        'Unit names should be unique.'
                    ),

                ColumnDefinition::make('Symbol')
                    ->required()
                    ->width(15)
                    ->example('kg')
                    ->comment(
                        'Short symbol. Example: kg, g, L, ml.'
                    )
                    ->instruction(
                        'Symbols should be unique within the same unit type.'
                    ),

                ColumnDefinition::make('Unit Type')
                    ->required()
                    ->width(18)
                    ->example('mass')
                    ->dropdown([
                        'mass',
                        'volume',
                        'count',
                    ])
                    ->comment(
                        'Choose the measurement category.'
                    )
                    ->instruction(
                        'Choose Mass, Volume or Count.'
                    ),

                ColumnDefinition::make('Base Multiplier')
                    ->required()
                    ->width(20)
                    ->format('#,##0.0000')
                    ->example(1000)
                    ->comment(
                        'Conversion factor to the base unit.'
                    )
                    ->instruction(
                        'Examples: Gram = 1, Kilogram = 1000, Milligram = 0.001.'
                    ),

                ColumnDefinition::make('Base Unit')
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
                        'Canonical unit used by the conversion engine.'
                    )
                    ->instruction(
                        'Choose the correct canonical base unit.'
                    ),

                ColumnDefinition::make('Is Active')
                    ->width(15)
                    ->example('Yes')
                    ->dropdown([
                        'Yes',
                        'No',
                    ])
                    ->comment(
                        'Choose whether this unit is active.'
                    )
                    ->instruction(
                        'Yes = Active, No = Inactive.'
                    ),

                ColumnDefinition::make('Use For Purchase')
                    ->width(20)
                    ->example('Yes')
                    ->dropdown([
                        'Yes',
                        'No',
                    ])
                    ->comment(
                        'Can this unit be used when purchasing stock?'
                    )
                    ->instruction(
                        'Controls availability during stock purchases.'
                    ),

                ColumnDefinition::make('Use For Recipe')
                    ->width(20)
                    ->example('Yes')
                    ->dropdown([
                        'Yes',
                        'No',
                    ])
                    ->comment(
                        'Can this unit be used inside recipes?'
                    )
                    ->instruction(
                        'Controls availability inside recipes.'
                    ),

            ])

            ->samples([

                [
                    'Kilogram',
                    'kg',
                    'mass',
                    1000,
                    'Gram',
                    'Yes',
                    'Yes',
                    'Yes',
                ],

            ])

            ->freezeHeader()

            ->autoFilter()

            ->protect(false)

            ->hideLookups();
    }
}
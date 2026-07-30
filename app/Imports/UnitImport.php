<?php

namespace App\Imports;

use App\Models\Unit;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UnitImport extends BaseImport implements
    ToModel,
    WithHeadingRow,
    WithValidation
{
    /**
     * Import Unit
     */
    public function model(array $row)
    {
        $this->rowStarted();

        $unit = Unit::create([

            'name'             => $row['name'],

            'symbol'           => strtoupper($row['symbol']),

            'unit_type'        => strtolower($row['unit_type']),

            'base_multiplier'  => $row['base_multiplier'],

            'base_unit'        => filter_var($row['base_unit'], FILTER_VALIDATE_BOOLEAN),

            'is_active'        => filter_var($row['is_active'], FILTER_VALIDATE_BOOLEAN),

            'use_for_purchase' => filter_var($row['use_for_purchase'], FILTER_VALIDATE_BOOLEAN),

            'use_for_recipe'   => filter_var($row['use_for_recipe'], FILTER_VALIDATE_BOOLEAN),

        ]);

        $this->rowImported();

        return $unit;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [

            'name' => [

                'required',

                Rule::unique('units', 'name'),

            ],

            'symbol' => [

                'required',

                Rule::unique('units', 'symbol'),

            ],

            'unit_type' => [

                'required',

                'in:weight,volume,count',

            ],

            'base_multiplier' => [

                'required',

                'numeric',

                'min:0.000001',

            ],

            'base_unit' => [

                'required',

            ],

            'is_active' => [

                'required',

            ],

            'use_for_purchase' => [

                'required',

            ],

            'use_for_recipe' => [

                'required',

            ],

        ];
    }

    /**
     * Validation Messages
     */
    public function customValidationMessages()
    {
        return [

            'name.required' => 'Unit name is required.',

            'name.unique' => 'Unit name already exists.',

            'symbol.required' => 'Unit symbol is required.',

            'symbol.unique' => 'Unit symbol already exists.',

            'unit_type.in' => 'Unit type must be weight, volume or count.',

            'base_multiplier.required' => 'Base multiplier is required.',

        ];
    }

    /**
     * Summary
     */
    public function summary(): array
    {
        $summary = parent::summary();

        $summary['metadata'] = [

            'module' => 'units',

        ];

        return $summary;
    }
}
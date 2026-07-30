<?php

namespace App\Imports;

use App\Models\Ingredient;
use App\Models\Unit;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class IngredientImport extends BaseImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsEmptyRows
{
    use Importable;

    /**
     * Import a single row.
     */
    public function model(array $row)
    {
        $this->rowStarted();

        if ($this->isEmptyRow($row)) {

            $this->rowSkipped();

            return null;

        }

        /*
        |--------------------------------------------------------------------------
        | Find Base Unit
        |--------------------------------------------------------------------------
        */

        $unit = Unit::where(

            'name',

            trim($row['base_unit'])

        )->first();

        if (!$unit) {

            $this->rowFailed();

            $this->addError(

                "Unknown Unit: {$row['base_unit']}"

            );

            return null;

        }

        /*
        |--------------------------------------------------------------------------
        | Check Existing Ingredient
        |--------------------------------------------------------------------------
        */

        $existing = Ingredient::where(

            'name',

            trim($row['name'])

        )->first();

        /*
        |--------------------------------------------------------------------------
        | Create / Update
        |--------------------------------------------------------------------------
        */

        Ingredient::updateOrCreate(

            [

                'name' => trim($row['name'])

            ],

            [

                'slug' => Str::slug($row['name']),

                'base_unit_id' => $unit->id,

                'is_active' => $row['is_active'] ?? true,

            ]

        );

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        if ($existing) {

            $this->rowUpdated();

        } else {

            $this->rowCreated();

        }

        $this->rowImported();

        return null;
    }

    /**
     * Validation Rules.
     */
    public function rules(): array
    {
        return [

            '*.name' => [

                'required',

                'string',

                'max:255'

            ],

            '*.base_unit' => [

                'required',

                'string'

            ],

            '*.is_active' => [

                'nullable',

                'boolean'

            ],

        ];
    }

    /**
     * Validation Messages.
     */
    public function customValidationMessages(): array
    {
        return [

            '*.name.required' => 'Ingredient name is required.',

            '*.base_unit.required' => 'Base Unit is required.',

        ];
    }
}
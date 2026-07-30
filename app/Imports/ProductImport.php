<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport extends BaseImport implements
    ToModel,
    WithHeadingRow,
    WithValidation
{
    /**
     * Import Product
     */
    public function model(array $row)
    {
        $this->rowStarted();

        $product = Product::create([

            'name' => $row['name'],

            'slug' => Str::slug($row['name']),

            'selling_price' => $row['selling_price'],

            'sales_unit' => $row['sales_unit'],

            'stock_on_hand' => $row['stock_on_hand'] ?? 0,

            'is_active' => true,

        ]);

        $this->rowImported();

        return $product;
    }

    /**
     * Validation
     */
    public function rules(): array
    {
        return [

            'name' => [

                'required',

                Rule::unique('products', 'name'),

            ],

            'selling_price' => [

                'required',

                'numeric',

                'min:0',

            ],

            'sales_unit' => [

                'required',

            ],

            'stock_on_hand' => [

                'nullable',

                'numeric',

                'min:0',

            ],

        ];
    }

    /**
     * Messages
     */
    public function customValidationMessages()
    {
        return [

            'name.required' => 'Product name is required.',

            'name.unique' => 'Product already exists.',

            'selling_price.required' => 'Selling price is required.',

            'sales_unit.required' => 'Sales unit is required.',

        ];
    }

    /**
     * Summary
     */
    public function summary(): array
    {
        $summary = parent::summary();

        $summary['metadata'] = [

            'module' => 'products',

        ];

        return $summary;
    }
}
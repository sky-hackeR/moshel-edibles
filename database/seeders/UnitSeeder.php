<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run()
    {
        $units = [

            /* ======================
             * MASS (BASE: g)
             * ====================== */
            [
                'name' => 'Gram',
                'symbol' => 'g',
                'unit_type' => 'mass',
                'base_unit' => 'g',
            ],
            [
                'name' => 'Kilogram',
                'symbol' => 'kg',
                'unit_type' => 'mass',
                'base_unit' => 'g',
            ],
            [
                'name' => 'Milligram',
                'symbol' => 'mg',
                'unit_type' => 'mass',
                'base_unit' => 'g',
                'use_for_purchase' => false,
            ],
            [
                'name' => 'Ounce',
                'symbol' => 'oz',
                'unit_type' => 'mass',
                'base_unit' => 'g',
            ],
            [
                'name' => 'Pound',
                'symbol' => 'lb',
                'unit_type' => 'mass',
                'base_unit' => 'g',
            ],

            /* ======================
             * VOLUME (BASE: ml)
             * ====================== */
            [
                'name' => 'Millilitre',
                'symbol' => 'ml',
                'unit_type' => 'volume',
                'base_unit' => 'ml',
            ],
            [
                'name' => 'Litre',
                'symbol' => 'L',
                'unit_type' => 'volume',
                'base_unit' => 'ml',
            ],
            [
                'name' => 'Teaspoon',
                'symbol' => 'tsp',
                'unit_type' => 'volume',
                'base_unit' => 'ml',
                'use_for_purchase' => false,
            ],
            [
                'name' => 'Tablespoon',
                'symbol' => 'tbsp',
                'unit_type' => 'volume',
                'base_unit' => 'ml',
                'use_for_purchase' => false,
            ],
            [
                'name' => 'Dessert Spoon',
                'symbol' => 'dsp',
                'unit_type' => 'volume',
                'base_unit' => 'ml',
                'use_for_purchase' => false,
            ],
            [
                'name' => 'Cup (Culinary)',
                'symbol' => 'cup',
                'unit_type' => 'volume',
                'base_unit' => 'ml',
                'use_for_purchase' => false,
            ],
            [
                'name' => 'Fluid Ounce (UK)',
                'symbol' => 'fl_oz',
                'unit_type' => 'volume',
                'base_unit' => 'ml',
            ],
            [
                'name' => 'Pint (UK)',
                'symbol' => 'pt',
                'unit_type' => 'volume',
                'base_unit' => 'ml',
            ],
            [
                'name' => 'Quart (UK)',
                'symbol' => 'qt',
                'unit_type' => 'volume',
                'base_unit' => 'ml',
            ],
            [
                'name' => 'Gallon (UK)',
                'symbol' => 'gal',
                'unit_type' => 'volume',
                'base_unit' => 'ml',
            ],

            /* ======================
             * COUNT (BASE: piece)
             * ====================== */
            [
                'name' => 'Piece',
                'symbol' => 'pcs',
                'unit_type' => 'count',
                'base_unit' => 'pcs',
            ],
            [
                'name' => 'Dozen',
                'symbol' => 'doz',
                'unit_type' => 'count',
                'base_unit' => 'pcs',
                'use_for_recipe' => false,
            ],
            [
                'name' => 'Half Dozen',
                'symbol' => 'half_doz',
                'unit_type' => 'count',
                'base_unit' => 'pcs',
                'use_for_recipe' => false,
            ],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(
                ['symbol' => $unit['symbol'], 'unit_type' => $unit['unit_type']],
                $unit
            );
        }
    }
}


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
            ['name' => 'Gram', 'symbol' => 'g', 'unit_type' => 'mass', 'base_unit' => 'g', 'base_multiplier' => 1],
            ['name' => 'Kilogram', 'symbol' => 'kg', 'unit_type' => 'mass', 'base_unit' => 'g', 'base_multiplier' => 1000],
            ['name' => 'Milligram', 'symbol' => 'mg', 'unit_type' => 'mass', 'base_unit' => 'g', 'base_multiplier' => 0.001],
            ['name' => 'Ounce', 'symbol' => 'oz', 'unit_type' => 'mass', 'base_unit' => 'g', 'base_multiplier' => 28.35],
            ['name' => 'Pound', 'symbol' => 'lb', 'unit_type' => 'mass', 'base_unit' => 'g', 'base_multiplier' => 453.59],

            /* ======================
             * VOLUME (BASE: ml)
             * ====================== */
            ['name' => 'Millilitre', 'symbol' => 'ml', 'unit_type' => 'volume', 'base_unit' => 'ml', 'base_multiplier' => 1],
            ['name' => 'Litre', 'symbol' => 'L', 'unit_type' => 'volume', 'base_unit' => 'ml', 'base_multiplier' => 1000],
            ['name' => 'Teaspoon', 'symbol' => 'tsp', 'unit_type' => 'volume', 'base_unit' => 'ml', 'base_multiplier' => 5],
            ['name' => 'Tablespoon', 'symbol' => 'tbsp', 'unit_type' => 'volume', 'base_unit' => 'ml', 'base_multiplier' => 15],
            ['name' => 'Dessert Spoon', 'symbol' => 'dsp', 'unit_type' => 'volume', 'base_unit' => 'ml', 'base_multiplier' => 10],
            ['name' => 'Cup (UK Culinary)', 'symbol' => 'cup', 'unit_type' => 'volume', 'base_unit' => 'ml', 'base_multiplier' => 250],
            ['name' => 'Fluid Ounce (UK)', 'symbol' => 'fl_oz', 'unit_type' => 'volume', 'base_unit' => 'ml', 'base_multiplier' => 28.41],
            ['name' => 'Pint (UK)', 'symbol' => 'pt', 'unit_type' => 'volume', 'base_unit' => 'ml', 'base_multiplier' => 568.26],
            ['name' => 'Quart (UK)', 'symbol' => 'qt', 'unit_type' => 'volume', 'base_unit' => 'ml', 'base_multiplier' => 1136.52],
            ['name' => 'Gallon (UK)', 'symbol' => 'gal', 'unit_type' => 'volume', 'base_unit' => 'ml', 'base_multiplier' => 4546.09],

            /* ======================
             * COUNT (BASE: piece)
             * ====================== */
            ['name' => 'Piece', 'symbol' => 'pcs', 'unit_type' => 'count', 'base_unit' => 'pcs', 'base_multiplier' => 1],
            ['name' => 'Dozen', 'symbol' => 'doz', 'unit_type' => 'count', 'base_unit' => 'pcs', 'base_multiplier' => 12],
            ['name' => 'Half Dozen', 'symbol' => 'half_doz', 'unit_type' => 'count', 'base_unit' => 'pcs', 'base_multiplier' => 6],
            ['name' => 'Crate (Eggs)', 'symbol' => 'crate', 'unit_type' => 'count', 'base_unit' => 'pcs', 'base_multiplier' => 30],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(
                ['symbol' => $unit['symbol'], 'unit_type' => $unit['unit_type']],
                $unit
            );
        }
    }
}
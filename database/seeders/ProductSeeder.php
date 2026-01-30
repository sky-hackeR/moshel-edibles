<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['Bread', 1200, 'Loaf'],
            ['Cake', 4500, 'Unit'],
            ['Meat Pie', 800, 'Pcs'],
            ['Sausage Roll', 700, 'Pcs'],
            ['Doughnut', 500, 'Pcs'],
            ['Cookies', 600, 'Pack'],
        ];

        foreach ($products as [$name, $price, $unit]) {
            Product::updateOrCreate(
                ['name' => $name],
                [
                    'slug'          => Str::slug($name),
                    'sales_unit'    => $unit,
                    'stock_on_hand' => 0,
                    'selling_price' => $price,
                    'is_active'     => true,
                ]
            );
        }
    }
}
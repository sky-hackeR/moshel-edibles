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
            ['Bread', 1200],
            ['Cake', 4500],
            ['Meat Pie', 800],
            ['Sausage Roll', 700],
            ['Doughnut', 500],
            ['Cookies', 600],
        ];

        foreach ($products as [$name, $price]) {
            Product::updateOrCreate(
                ['name' => $name],
                [
                    'slug'          => Str::slug($name),
                    'selling_price' => $price,
                    'is_active'     => true,
                ]
            );
        }
    }
}

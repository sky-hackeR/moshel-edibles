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
            'Bread',
            'Cake',
            'Meat Pie',
            'Sausage Roll',
            'Doughnut',
            'Cookies',
        ];

        foreach ($products as $name) {
            Product::updateOrCreate(
                ['name' => $name],
                [
                    'slug'      => Str::slug($name),
                    'is_active' => true,
                ]
            );
        }
    }
}

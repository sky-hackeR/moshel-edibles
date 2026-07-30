<?php

namespace App\Services\BulkOperations;

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\StockIn;
use App\Models\Unit;

use App\Imports\IngredientImport;
use App\Imports\ProductImport;
use App\Imports\RecipeImport;
use App\Imports\StockImport;
use App\Imports\UnitImport;

use App\Exports\IngredientTemplateExport;
use App\Exports\ProductTemplateExport;
use App\Exports\RecipeTemplateExport;
use App\Exports\StockTemplateExport;
use App\Exports\UnitTemplateExport;

class BulkRegistry
{
    /**
     * Registered Bulk Modules
     */
    protected static array $modules = [

        'ingredient' => [

            'label'       => 'Ingredients',

            'model'       => Ingredient::class,

            'icon'        => 'bx bx-box',

            'color'       => 'primary',

            'import'      => IngredientImport::class,

            'export'      => IngredientTemplateExport::class,

            'template'    => 'ingredients-template.xlsx',

            'extensions'  => ['xlsx','xls','csv'],

            'max_size'    => 5120,

        ],

        'product' => [

            'label'       => 'Products',

            'model'       => Product::class,

            'icon'        => 'bx bx-food-menu',

            'color'       => 'success',

            'import'      => ProductImport::class,

            'export'      => ProductTemplateExport::class,

            'template'    => 'products-template.xlsx',

            'extensions'  => ['xlsx','xls','csv'],

            'max_size'    => 5120,

        ],

        'recipe' => [

            'label'       => 'Recipes',

            'model'       => Recipe::class,

            'icon'        => 'bx bx-receipt',

            'color'       => 'warning',

            'import'      => RecipeImport::class,

            'export'      => RecipeTemplateExport::class,

            'template'    => 'recipes-template.xlsx',

            'extensions'  => ['xlsx','xls','csv'],

            'max_size'    => 5120,

        ],

        'stock' => [

            'label'       => 'Stock In',

            'model'       => StockIn::class,

            'icon'        => 'bx bx-package',

            'color'       => 'info',

            'import'      => StockImport::class,

            'export'      => StockTemplateExport::class,

            'template'    => 'stock-template.xlsx',

            'extensions'  => ['xlsx','xls','csv'],

            'max_size'    => 5120,

        ],

        'unit' => [

            'label'       => 'Units',

            'model'       => Unit::class,

            'icon'        => 'bx bx-ruler',

            'color'       => 'secondary',

            'import'      => UnitImport::class,

            'export'      => UnitTemplateExport::class,

            'template'    => 'units-template.xlsx',

            'extensions'  => ['xlsx','xls','csv'],

            'max_size'    => 2048,

        ],

    ];

    /**
     * Get all modules.
     */
    public static function all(): array
    {
        return self::$modules;
    }

    /**
     * Get module configuration.
     */
    public static function module(string $module): ?array
    {
        return self::$modules[$module] ?? null;
    }

    /**
     * Determine if module exists.
     */
    public static function exists(string $module): bool
    {
        return isset(self::$modules[$module]);
    }

    /**
     * Generic accessor.
     */
    public static function get(string $module, ?string $key = null)
    {
        if (!self::exists($module)) {
            return null;
        }

        if ($key === null) {
            return self::$modules[$module];
        }

        return self::$modules[$module][$key] ?? null;
    }

    /**
     * Import class.
     */
    public static function import(string $module): ?string
    {
        return self::get($module, 'import');
    }

    /**
     * Export class.
     */
    public static function export(string $module): ?string
    {
        return self::get($module, 'export');
    }

    /**
     * Model class.
     */
    public static function model(string $module): ?string
    {
        return self::get($module, 'model');
    }

    /**
     * Display label.
     */
    public static function label(string $module): ?string
    {
        return self::get($module, 'label');
    }

    /**
     * Template filename.
     */
    public static function template(string $module): ?string
    {
        return self::get($module, 'template');
    }

    /**
     * Icon.
     */
    public static function icon(string $module): ?string
    {
        return self::get($module, 'icon');
    }

    /**
     * Theme color.
     */
    public static function color(string $module): ?string
    {
        return self::get($module, 'color');
    }

    /**
     * Allowed extensions.
     */
    public static function extensions(string $module): array
    {
        return self::get($module, 'extensions') ?? [];
    }

    /**
     * Upload size.
     */
    public static function maxSize(string $module): int
    {
        return self::get($module, 'max_size') ?? 5120;
    }
}
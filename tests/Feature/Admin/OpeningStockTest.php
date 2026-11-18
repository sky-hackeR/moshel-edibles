<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\OpeningStock;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class OpeningStockTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): Admin
    {
        return Admin::create([
            'name' => 'Stock Officer',
            'email' => 'stock-officer-' . uniqid() . '@example.test',
            'password' => bcrypt('secret123'),
        ]);
    }

    private function seedBaseUnit(): Unit
    {
        return Unit::create([
            'name' => 'Gram',
            'symbol' => 'g',
            'unit_type' => 'mass',
            'base_multiplier' => 1.0,
            'base_unit' => 'g',
            'is_active' => true,
            'use_for_purchase' => true,
            'use_for_recipe' => true,
        ]);
    }

    public function test_guest_cannot_access_opening_stock()
    {
        $response = $this->get('/admin/openingStock');
        $response->assertRedirect('/admin');
    }

    public function test_admin_can_view_redesigned_opening_stock_page()
    {
        $admin = $this->createAdmin();
        $unit = $this->seedBaseUnit();

        $product = Product::create([
            'name' => 'Artisan Bread',
            'slug' => 'artisan-bread-' . Str::random(5),
            'selling_price' => 1500,
            'sales_unit' => 'Loaf',
            'stock_on_hand' => 20,
            'is_active' => true,
        ]);

        $ingredient = Ingredient::create([
            'name' => 'Bakers Yeast',
            'slug' => 'bakers-yeast-' . Str::random(5),
            'base_unit_id' => $unit->id,
            'is_active' => true,
        ]);

        $openingStock = OpeningStock::create([
            'item_type' => 'product',
            'product_id' => $product->id,
            'quantity' => 15,
            'reason' => 'Commencement audit count',
            'admin_id' => $admin->id,
        ]);

        // Add 30kg ingredient for 25,000 (average_cost = 0.833333)
        $ingOpeningStock = OpeningStock::create([
            'item_type' => 'ingredient',
            'ingredient_id' => $ingredient->id,
            'quantity' => 30000,
            'average_cost' => 0.833333,
            'reason' => '30kg flour bulk initialization',
            'admin_id' => $admin->id,
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/openingStock');

        $response->assertStatus(200);
        $response->assertSee('Opening Stock Initialization', false);
        $response->assertSee('Stock Initialization Workbench');
        $response->assertSee('Opening Stock Audit Ledger');
        $response->assertSee('Artisan Bread');
        $response->assertSee('Bakers Yeast');
        $response->assertSee('Commencement audit count');
        $response->assertSee('viewOpeningStockModal' . $openingStock->id);
        $response->assertSee('viewOpeningStockModal' . $ingOpeningStock->id);
        // Verify product valuation: 15 * 1500 = 22,500.00
        $response->assertSee('22,500.00');
        // Verify ingredient valuation avoids 24999.99 and shows clean 25,000.00
        $response->assertSee('25,000.00');
        $response->assertDontSee('24,999.99');
        $response->assertDontSee('24999.99');
        $response->assertViewHas('products');
        $response->assertViewHas('ingredients');
        $response->assertViewHas('openingStocks');
    }

    public function test_admin_can_add_product_opening_stock()
    {
        $admin = $this->createAdmin();

        $product = Product::create([
            'name' => 'Meat Pie',
            'slug' => 'meat-pie-' . Str::random(5),
            'selling_price' => 1200,
            'sales_unit' => 'Piece',
            'stock_on_hand' => 10,
            'is_active' => true,
        ]);

        $payload = [
            'product_id' => $product->id,
            'quantity' => 40,
            'reason' => 'Physical opening stock verification',
        ];

        $response = $this->actingAs($admin, 'admin')->post('/admin/addProductOpeningStock', $payload);

        $response->assertRedirect();
        
        // Assert opening stock recorded
        $this->assertDatabaseHas('opening_stocks', [
            'item_type' => 'product',
            'product_id' => $product->id,
            'quantity' => 40,
            'admin_id' => $admin->id,
        ]);

        // Assert product stock incremented (10 + 40 = 50)
        $product->refresh();
        $this->assertEquals(50, $product->stock_on_hand);
    }

    public function test_admin_can_add_ingredient_opening_stock_with_weighted_costing()
    {
        $admin = $this->createAdmin();
        $unit = $this->seedBaseUnit();

        $ingredient = Ingredient::create([
            'name' => 'White Sugar',
            'slug' => 'white-sugar-' . Str::random(5),
            'base_unit_id' => $unit->id,
            'is_active' => true,
        ]);

        // Pre-existing inventory
        Inventory::create([
            'ingredient_id' => $ingredient->id,
            'quantity' => 10000,
            'average_cost' => 1.00,
        ]);

        // Adding 10,000g worth ₦20,000 (cost per gram = ₦2.00)
        // Weighted cost should become (10000*1 + 10000*2) / 20000 = ₦1.50
        $payload = [
            'ingredient_id' => $ingredient->id,
            'quantity' => 10000,
            'total_value' => 20000,
            'reason' => 'Initial warehouse intake',
        ];

        $response = $this->actingAs($admin, 'admin')->post('/admin/addIngredientOpeningStock', $payload);

        $response->assertRedirect();

        $this->assertDatabaseHas('opening_stocks', [
            'item_type' => 'ingredient',
            'ingredient_id' => $ingredient->id,
            'quantity' => 10000,
            'average_cost' => 2.00,
        ]);

        $inventory = Inventory::where('ingredient_id', $ingredient->id)->first();
        $this->assertNotNull($inventory);
        $this->assertEquals(20000, $inventory->quantity);
        $this->assertEquals(1.50, $inventory->average_cost);
    }

    public function test_opening_stock_validates_required_fields()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin, 'admin')->post('/admin/addProductOpeningStock', []);
        $response->assertRedirect();
        $this->assertEquals(0, OpeningStock::count());

        $response = $this->actingAs($admin, 'admin')->post('/admin/addIngredientOpeningStock', []);
        $response->assertRedirect();
        $this->assertEquals(0, OpeningStock::count());
    }
}

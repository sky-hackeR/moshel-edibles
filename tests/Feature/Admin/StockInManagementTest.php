<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Unit;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\StockIn;
use App\Models\StockInItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\Stock\StockInRecorded;

class StockInManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): Admin
    {
        return Admin::create([
            'name' => 'Purchasing Manager',
            'email' => 'purchasing-' . uniqid() . '@example.test',
            'password' => bcrypt('secret123'),
        ]);
    }

    private function seedUnits(): array
    {
        $baseKg = Unit::create([
            'name' => 'Kilogram',
            'symbol' => 'kg',
            'unit_type' => 'mass',
            'base_multiplier' => 1000.0,
            'base_unit' => 'g',
            'is_active' => true,
            'use_for_purchase' => true,
            'use_for_recipe' => true,
        ]);

        $baseGram = Unit::create([
            'name' => 'Gram',
            'symbol' => 'g',
            'unit_type' => 'mass',
            'base_multiplier' => 1.0,
            'base_unit' => 'g',
            'is_active' => true,
            'use_for_purchase' => true,
            'use_for_recipe' => true,
        ]);

        return [$baseKg, $baseGram];
    }

    public function test_guest_cannot_access_stock_in_or_record_purchases()
    {
        $this->get('/admin/stockIn')->assertRedirect('/admin');
        $this->post('/admin/newStockIn', [])->assertRedirect('/admin');
    }

    public function test_admin_can_view_redesigned_stock_in_page_and_table()
    {
        $admin = $this->createAdmin();
        [$kg, $gram] = $this->seedUnits();

        $ingredient = Ingredient::create([
            'name' => 'Baking Flour',
            'slug' => 'baking-flour',
            'base_unit_id' => $gram->id,
            'reorder_level' => 5000,
            'is_active' => true,
        ]);

        $stockIn = StockIn::create([
            'reference' => 'STK-TEST1234',
            'purchase_date' => now()->toDateString(),
            'supplier' => 'Flour Mills of Nigeria',
            'note' => 'Paid on invoice #9021',
            'created_by' => $admin->id,
        ]);

        StockInItem::create([
            'stock_in_id' => $stockIn->id,
            'ingredient_id' => $ingredient->id,
            'unit_id' => $kg->id,
            'quantity' => 25.0,
            'unit_price' => 1000.0,
            'total_price' => 25000.0,
            'base_quantity' => 25000.0,
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/stockIn');

        $response->assertStatus(200);
        $response->assertSee('Stock In (Procurement)');
        $response->assertSee('Purchases Today');
        $response->assertSee('Monthly Spend');
        $response->assertSee('Purchase Batches');
        $response->assertSee('id="datatable-buttons"', false);
        $response->assertSee('STK-TEST1234');
        $response->assertSee('Flour Mills of Nigeria');
        $response->assertSee('₦25,000.00');
    }

    public function test_admin_can_record_new_stock_purchase_with_weighted_cost()
    {
        Mail::fake();

        $admin = $this->createAdmin();
        [$kg, $gram] = $this->seedUnits();

        $ingredient = Ingredient::create([
            'name' => 'Granulated Sugar',
            'slug' => 'granulated-sugar',
            'base_unit_id' => $gram->id,
            'reorder_level' => 3000,
            'is_active' => true,
        ]);

        // Pre-existing stock: 10,000 g @ ₦1.00/g = ₦10,000
        Inventory::create([
            'ingredient_id' => $ingredient->id,
            'quantity' => 10000,
            'average_cost' => 1.00,
        ]);

        // Purchase: 10 kg (= 10,000 g) for ₦20,000 (rate = ₦2.00/g)
        $payload = [
            'purchase_date' => now()->toDateString(),
            'supplier' => 'Golden Penny Sugar',
            'note' => 'Batch restock',
            'items' => [
                [
                    'ingredient_id' => $ingredient->id,
                    'quantity' => 10,
                    'unit_id' => $kg->id,
                    'total_price' => 20000,
                ],
            ],
        ];

        $response = $this->actingAs($admin, 'admin')->post('/admin/newStockIn', $payload);

        $response->assertRedirect();

        $this->assertDatabaseHas('stock_ins', [
            'supplier' => 'Golden Penny Sugar',
            'created_by' => $admin->id,
        ]);

        $this->assertDatabaseHas('stock_in_items', [
            'ingredient_id' => $ingredient->id,
            'quantity' => 10,
            'base_quantity' => 10000,
            'total_price' => 20000,
        ]);

        // Weighted Average Cost:
        // (10,000 * 1.00 + 10,000 * 2.00) / 20,000 = ₦1.50/g
        $inventory = Inventory::where('ingredient_id', $ingredient->id)->first();
        $this->assertEquals(20000, (float) $inventory->quantity);
        $this->assertEquals(1.50, round((float) $inventory->average_cost, 2));

        Mail::assertSent(StockInRecorded::class);
    }

    public function test_stock_in_validation_prevents_empty_items()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin, 'admin')->post('/admin/newStockIn', [
            'purchase_date' => now()->toDateString(),
            'items' => [],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('stock_ins', 0);
    }
}

<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\Unit;
use App\Models\Ingredient;
use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InventoryManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): Admin
    {
        return Admin::create([
            'name' => 'Stock Administrator',
            'email' => 'admin-inventory-' . uniqid() . '@example.test',
            'password' => bcrypt('secret123'),
        ]);
    }

    private function createStaff(): Staff
    {
        return Staff::create([
            'name' => 'Kitchen Staff',
            'email' => 'staff-inventory-' . uniqid() . '@example.test',
            'password' => bcrypt('secret123'),
        ]);
    }

    private function seedBaseUnit(string $symbol = 'g', string $name = 'Gram', string $type = 'mass'): Unit
    {
        return Unit::create([
            'name' => $name,
            'symbol' => $symbol,
            'unit_type' => $type,
            'base_multiplier' => 1.0,
            'base_unit' => $symbol,
            'is_active' => true,
            'use_for_purchase' => true,
            'use_for_recipe' => true,
        ]);
    }

    public function test_guest_cannot_access_admin_or_staff_inventory()
    {
        $this->get('/admin/inventory')->assertRedirect('/admin');
        $this->get('/staff/inventory')->assertRedirect('/staff');
    }

    public function test_admin_can_view_redesigned_inventory_page_with_kpis_and_table()
    {
        $admin = $this->createAdmin();
        $unit = $this->seedBaseUnit();

        $ingredient = Ingredient::create([
            'name' => 'Baking Flour',
            'slug' => 'baking-flour',
            'base_unit_id' => $unit->id,
            'reorder_level' => 2000,
            'is_active' => true,
        ]);

        $inventory = Inventory::create([
            'ingredient_id' => $ingredient->id,
            'quantity' => 10000.000,
            'average_cost' => 1.50,
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/inventory');

        $response->assertStatus(200);
        $response->assertSee('Inventory Management');
        $response->assertSee('Raw Materials Inventory');
        $response->assertSee('Cataloged Materials');
        $response->assertSee('Stock Alerts');
        $response->assertSee('Inventory Valuation');
        $response->assertSee('Optimal Health');
        $response->assertSee('id="datatable-buttons"', false);
        $response->assertSee('Baking Flour');
        $response->assertSee('10,000.000');
        $response->assertSee('₦15,000.00'); // Valuation: 10,000 * 1.50
        $response->assertSee('Optimal');
    }

    public function test_staff_can_view_redesigned_inventory_page_with_kpis_and_table()
    {
        $staff = $this->createStaff();
        $unit = $this->seedBaseUnit('ml', 'Milliliter', 'volume');

        $ingredient = Ingredient::create([
            'name' => 'Vanilla Extract',
            'slug' => 'vanilla-extract',
            'base_unit_id' => $unit->id,
            'reorder_level' => 500,
            'is_active' => true,
        ]);

        $inventory = Inventory::create([
            'ingredient_id' => $ingredient->id,
            'quantity' => 3500.000,
            'average_cost' => 4.20,
        ]);

        $response = $this->actingAs($staff, 'staff')->get('/staff/inventory');

        $response->assertStatus(200);
        $response->assertSee('Inventory Management');
        $response->assertSee('Raw Materials Stock');
        $response->assertSee('Raw Materials Tracked');
        $response->assertSee('Low Stock Alerts');
        $response->assertSee('Depleted Stock');
        $response->assertSee('Ready for Production');
        $response->assertSee('id="datatable-buttons"', false);
        $response->assertSee('Vanilla Extract');
        $response->assertSee('3,500.000');
        $response->assertSee('Optimal');
    }

    public function test_low_stock_and_out_of_stock_kpis_and_status_badges()
    {
        $admin = $this->createAdmin();
        $staff = $this->createStaff();
        $unit = $this->seedBaseUnit();

        // 1. Depleted Item (qty = 0)
        $depletedIng = Ingredient::create([
            'name' => 'Dry Yeast',
            'slug' => 'dry-yeast',
            'base_unit_id' => $unit->id,
            'reorder_level' => 1000,
            'is_active' => true,
        ]);
        Inventory::create([
            'ingredient_id' => $depletedIng->id,
            'quantity' => 0,
            'average_cost' => 2.00,
        ]);

        // 2. Low Stock Item (qty <= threshold)
        $lowIng = Ingredient::create([
            'name' => 'Cane Sugar',
            'slug' => 'cane-sugar',
            'base_unit_id' => $unit->id,
            'reorder_level' => 2000,
            'is_active' => true,
        ]);
        Inventory::create([
            'ingredient_id' => $lowIng->id,
            'quantity' => 800,
            'average_cost' => 1.20,
        ]);

        // Admin view assertions
        $adminResp = $this->actingAs($admin, 'admin')->get('/admin/inventory');
        $adminResp->assertStatus(200);
        $adminResp->assertSee('Out of Stock');
        $adminResp->assertSee('Low Stock');
        $adminResp->assertSee('1 Depleted, 1 Low');

        // Staff view assertions
        $staffResp = $this->actingAs($staff, 'staff')->get('/staff/inventory');
        $staffResp->assertStatus(200);
        $staffResp->assertSee('Out of Stock');
        $staffResp->assertSee('Low Stock');
        $staffResp->assertSee('Dry Yeast');
        $staffResp->assertSee('Cane Sugar');
    }
}

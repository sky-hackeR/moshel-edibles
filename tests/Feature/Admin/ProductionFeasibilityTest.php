<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\Unit;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductionFeasibilityTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): Admin
    {
        return Admin::create([
            'name' => 'Production Admin',
            'email' => 'prod-admin-' . uniqid() . '@example.test',
            'password' => bcrypt('secret123'),
        ]);
    }

    private function createStaff(): Staff
    {
        return Staff::create([
            'name' => 'Baker Staff',
            'email' => 'baker-' . uniqid() . '@example.test',
            'password' => bcrypt('secret123'),
        ]);
    }

    public function test_admin_can_view_production_page_with_matrix()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin, 'admin')->get('/admin/production');

        $response->assertStatus(200);
        $response->assertSee('Production Feasibility Engine');
        $response->assertSee('Live Pantry Audit');
        $response->assertViewHas('productionMatrix');
    }

    public function test_admin_can_poll_live_inventory_status()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.production.inventoryStatus'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'timestamp',
            'inventories',
        ]);
        $response->assertJson(['status' => 'success']);
    }

    public function test_staff_can_poll_live_inventory_status()
    {
        $staff = $this->createStaff();

        $response = $this->actingAs($staff, 'staff')->get(route('staff.production.inventoryStatus'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'timestamp',
            'inventories',
        ]);
        $response->assertJson(['status' => 'success']);
    }
}

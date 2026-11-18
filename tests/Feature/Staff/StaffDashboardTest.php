<?php

namespace Tests\Feature\Staff;

use Tests\TestCase;
use App\Models\Staff;
use App\Models\Product;
use App\Models\Production;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class StaffDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function createStaff(): Staff
    {
        return Staff::create([
            'name' => 'Jane Cashier',
            'email' => 'jane-' . uniqid() . '@example.test',
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_guest_cannot_access_staff_dashboard()
    {
        $this->get('/staff/home')->assertRedirect('/staff');
    }

    public function test_staff_can_view_redesigned_dashboard_with_kpis_and_actions()
    {
        $staff = $this->createStaff();

        $product = Product::create([
            'name' => 'Sourdough Loaf',
            'slug' => 'sourdough-loaf',
            'selling_price' => 2500,
            'sales_unit' => 'loaf',
            'stock_on_hand' => 15,
            'is_active' => true,
        ]);

        $production = Production::create([
            'product_id' => $product->id,
            'staff_id' => $staff->id,
            'quantity' => 20,
            'unit_cost' => 1000,
            'total_cost' => 20000,
            'selling_price' => 2500,
            'expected_revenue' => 50000,
            'profit' => 30000,
            'produced_at' => now(),
        ]);

        $sale = Sale::create([
            'reference_no' => 'MOS-STAFFTEST01',
            'user_id' => $staff->id,
            'user_type' => 'staff',
            'total_amount' => 5000,
            'discount_amount' => 0,
            'payable_amount' => 5000,
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'order_status' => 'completed',
        ]);

        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 2500,
            'subtotal' => 5000,
        ]);

        $response = $this->actingAs($staff, 'staff')->get('/staff/home');

        $response->assertOk();
        $response->assertSee('Welcome back, Jane!');
        $response->assertSee('My Sales Today');
        $response->assertSee('₦5,000.00');
        $response->assertSee('My Production Today');
        $response->assertSee('20');
        $response->assertSee('Storewide Sales');
        $response->assertSee('POS Terminal');
        $response->assertSee('Record Production');
        $response->assertSee('Raw Inventory');
        $response->assertSee('Counter Stock Ready');
        $response->assertSee('Sourdough Loaf');
        $response->assertSee('My Shift Operations Log');
        $response->assertSee('MOS-STAFFTEST01');
    }
}

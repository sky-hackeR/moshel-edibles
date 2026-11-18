<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Staff;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class POSTerminalTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(string $email = 'admin@example.test', string $name = 'Super Admin'): Admin
    {
        return Admin::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('secret-password'),
        ]);
    }

    private function createStaff(string $email = 'staff@example.test', string $name = 'Floor Cashier'): Staff
    {
        return Staff::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('secret-password'),
        ]);
    }

    private function createProduct(string $name = 'Premium Sweet Loaf', float $stock = 25.0, float $price = 1500.0, string $unit = 'Loaf'): Product
    {
        return Product::create([
            'name' => $name,
            'slug' => Str::slug($name . '-' . Str::random(5)),
            'selling_price' => $price,
            'sales_unit' => $unit,
            'stock_on_hand' => $stock,
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_admin_or_staff_pos()
    {
        $this->get('/admin/pos')->assertRedirect();
        $this->get('/staff/pos')->assertRedirect();
    }

    public function test_admin_can_view_redesigned_pos_terminal()
    {
        $admin = $this->createAdmin();
        $product = $this->createProduct('Premium Sweet Loaf', 25.0, 1500.0, 'Loaf');

        $response = $this->actingAs($admin, 'admin')->get('/admin/pos');

        $response->assertStatus(200);
        $response->assertSee('SALES TERMINAL');
        $response->assertSee('Premium Sweet Loaf');
        $response->assertSee('Active Ticket');
        $response->assertSee('CHARGE');
        $response->assertSee('posSearchInput');
        $response->assertSee('checkoutModal');
        $response->assertSee('receiptModal');
    }

    public function test_staff_can_view_redesigned_pos_terminal()
    {
        $staff = $this->createStaff();
        $product = $this->createProduct('Special Baguette', 12.0, 850.0, 'Pcs');

        $response = $this->actingAs($staff, 'staff')->get('/staff/pos');

        $response->assertStatus(200);
        $response->assertSee('SALES TERMINAL');
        $response->assertSee('Special Baguette');
        $response->assertSee('Active Ticket');
        $response->assertSee('posSearchInput');
    }

    public function test_admin_can_process_sale_with_receipt_data()
    {
        $admin = $this->createAdmin();
        $product = $this->createProduct('Wheat Bread', 10.0, 1200.0, 'Loaf');

        $payload = [
            'payment_method' => 'Cash',
            'cart_items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'price' => 1200,
                ],
            ],
            'total_amount' => 2400,
            'discount_amount' => 200,
            'payable_amount' => 2200,
            'notes' => 'Counter pickup - Table 4',
        ];

        $response = $this->actingAs($admin, 'admin')
            ->postJson('/admin/processSale', $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Sale Completed Successfully!',
        ]);
        $response->assertJsonStructure([
            'success',
            'message',
            'reference_no',
            'sale' => [
                'reference_no',
                'created_at',
                'seller_name',
                'total_amount',
                'discount_amount',
                'payable_amount',
                'payment_method',
                'notes',
            ],
        ]);

        $this->assertEquals(8, $product->fresh()->stock_on_hand);
        $this->assertDatabaseHas('sales', [
            'payable_amount' => 2200,
            'payment_method' => 'Cash',
            'notes' => 'Counter pickup - Table 4',
            'user_type' => 'admin',
        ]);
    }

    public function test_staff_can_process_sale_with_receipt_data()
    {
        $staff = $this->createStaff();
        $product = $this->createProduct('Sardine Roll', 15.0, 500.0, 'Pcs');

        $payload = [
            'payment_method' => 'Transfer',
            'cart_items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 3,
                    'price' => 500,
                ],
            ],
            'total_amount' => 1500,
            'discount_amount' => 0,
            'payable_amount' => 1500,
            'notes' => 'Transfer confirmed via GTBank',
        ];

        $response = $this->actingAs($staff, 'staff')
            ->postJson('/staff/processSale', $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Sale Completed Successfully!',
        ]);

        $this->assertEquals(12, $product->fresh()->stock_on_hand);
        $this->assertDatabaseHas('sales', [
            'payable_amount' => 1500,
            'payment_method' => 'Transfer',
            'user_type' => 'staff',
        ]);
    }

    public function test_sale_fails_when_stock_is_insufficient()
    {
        $admin = $this->createAdmin();
        $product = $this->createProduct('Cupcake', 2.0, 300.0, 'Pcs');

        $payload = [
            'payment_method' => 'Cash',
            'cart_items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5, // exceeds 2
                    'price' => 300,
                ],
            ],
            'total_amount' => 1500,
            'discount_amount' => 0,
            'payable_amount' => 1500,
        ];

        $response = $this->actingAs($admin, 'admin')
            ->postJson('/admin/processSale', $payload);

        $response->assertStatus(500);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertEquals(2, $product->fresh()->stock_on_hand);
    }
}

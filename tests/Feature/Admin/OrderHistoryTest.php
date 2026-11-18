<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderHistoryTest extends TestCase
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

    private function createProduct(string $name = 'Jumbo Bread', float $price = 2000.0): Product
    {
        return Product::create([
            'name' => $name,
            'slug' => Str::slug($name . '-' . Str::random(5)),
            'selling_price' => $price,
            'sales_unit' => 'Loaf',
            'stock_on_hand' => 50,
            'is_active' => true,
        ]);
    }

    private function createSale(string $userType, int $userId, float $amount = 4000.0, string $method = 'Cash', ?int $customerId = null): Sale
    {
        $sale = Sale::create([
            'reference_no' => 'REC-' . strtoupper(Str::random(8)),
            'user_id' => $userId,
            'user_type' => $userType,
            'customer_id' => $customerId,
            'total_amount' => $amount,
            'discount_amount' => 200,
            'payable_amount' => $amount - 200,
            'payment_method' => $method,
            'order_status' => 'completed',
            'payment_status' => 'paid',
            'paid_at' => now(),
            'notes' => 'Test transaction note',
        ]);

        $product = $this->createProduct('Loaf ' . Str::random(3), 1900.0);
        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 1900.0,
            'subtotal' => 3800.0,
        ]);

        return $sale;
    }

    public function test_guest_cannot_access_sales_history()
    {
        $this->get('/admin/salesHistory')->assertRedirect();
        $this->get('/staff/salesHistory')->assertRedirect();
    }

    public function test_admin_can_view_redesigned_sales_history_page()
    {
        $admin = $this->createAdmin();
        $sale = $this->createSale('admin', $admin->id, 5000.0, 'Cash');

        $response = $this->actingAs($admin, 'admin')->get('/admin/salesHistory');

        $response->assertStatus(200);
        $response->assertSee('Sales & Order History', false);
        $response->assertSee('Gross Paid Sales');
        $response->assertSee('Total Orders Logged');
        $response->assertSee('Counter POS Sales');
        $response->assertSee('Online Store Orders');
        $response->assertSee($sale->reference_no);
        $response->assertSee('saleDetailsModal');
        $response->assertSee('thermalPrintContent');
    }

    public function test_staff_can_view_redesigned_sales_history_page()
    {
        $staff = $this->createStaff();
        $sale = $this->createSale('staff', $staff->id, 3500.0, 'Transfer');

        $response = $this->actingAs($staff, 'staff')->get('/staff/salesHistory');

        $response->assertStatus(200);
        $response->assertSee('My Sales & Order History', false);
        $response->assertSee('My Total Settled Sales');
        $response->assertSee('Cash Collected');
        $response->assertSee($sale->reference_no);
        $response->assertSee('saleDetailsModal');
    }

    public function test_admin_can_fetch_sale_receipt_details_json()
    {
        $admin = $this->createAdmin();
        $sale = $this->createSale('admin', $admin->id, 6000.0, 'Card');

        $response = $this->actingAs($admin, 'admin')
            ->getJson('/admin/sales/details/' . $sale->reference_no);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'sale' => [
                'reference_no' => $sale->reference_no,
                'payment_method' => 'Card',
                'payable_amount' => 5800.0,
            ],
        ]);
        $response->assertJsonStructure([
            'success',
            'sale' => [
                'reference_no',
                'created_at',
                'items',
            ],
        ]);
    }

    public function test_staff_can_fetch_sale_receipt_details_json()
    {
        $staff = $this->createStaff();
        $sale = $this->createSale('staff', $staff->id, 4500.0, 'Cash');

        $response = $this->actingAs($staff, 'staff')
            ->getJson('/staff/sales/details/' . $sale->reference_no);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'sale' => [
                'reference_no' => $sale->reference_no,
                'payment_method' => 'Cash',
                'payable_amount' => 4300.0,
            ],
        ]);
    }

    public function test_admin_can_view_customer_orders_page_with_receipt_modal()
    {
        $admin = $this->createAdmin();
        $customer = Customer::create([
            'name' => 'Chioma Obi',
            'email' => 'chioma@example.test',
            'password' => Hash::make('secret-password'),
            'is_active' => true,
        ]);

        $sale = $this->createSale('customer', $customer->id, 7500.0, 'Transfer', $customer->id);

        $response = $this->actingAs($admin, 'admin')->get(route('customers.orders', $customer));

        $response->assertStatus(200);
        $response->assertSee('Customer Orders & Ledger', false);
        $response->assertSee('Chioma Obi');
        $response->assertSee($sale->reference_no);
        $response->assertSee('saleDetailsModal');
    }
}

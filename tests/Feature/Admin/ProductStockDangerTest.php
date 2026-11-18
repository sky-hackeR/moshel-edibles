<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\AuditLog;
use App\Models\Product;
use App\Models\Staff;
use App\Mail\Product\StockCleared;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductStockDangerTest extends TestCase
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

    private function createStaff(string $email = 'staff@example.test', string $name = 'Production Staff'): Staff
    {
        return Staff::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('secret-password'),
        ]);
    }

    private function createProduct(string $name = 'Red Velvet Cake', float $stock = 25.0, float $price = 5000.0): Product
    {
        return Product::create([
            'name' => $name,
            'slug' => Str::slug($name),
            'sales_unit' => 'Box',
            'selling_price' => $price,
            'is_active' => true,
            'stock_on_hand' => $stock,
        ]);
    }

    public function test_guests_cannot_access_product_stock_danger_zone()
    {
        $response = $this->get(route('admin.products.stock'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_staff_cannot_access_product_stock_danger_zone()
    {
        $staff = $this->createStaff();

        $response = $this->actingAs($staff, 'staff')->get(route('admin.products.stock'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_product_stock_danger_zone()
    {
        $admin = $this->createAdmin();
        $product = $this->createProduct('Chocolate Fudge Brownies', 40.0, 3500.0);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.products.stock'));

        $response->assertStatus(200);
        $response->assertSee('Stock Control — Danger Zone');
        $response->assertSee('Chocolate Fudge Brownies');
        $response->assertSee('40 Box');
        $response->assertSee('₦3,500.00');
    }

    public function test_admin_can_clear_single_product_stock_with_email_and_audit_log()
    {
        Mail::fake();

        $admin = $this->createAdmin('lead_admin@moshedibles.com', 'Lead Executive');
        $product = $this->createProduct('Vanilla Dream Sponge', 15.0, 4200.0);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.products.clearStock'), [
            'product_id' => $product->id,
            'reason' => 'Batch Spoilage / Quality Issue',
            'notes' => 'Damaged during packaging audit.',
            'confirm_deplete' => '1',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        // 1. Verify stock is zeroed in database
        $this->assertEquals(0, $product->fresh()->stock_on_hand);

        // Verify admin notification was sent
        Mail::assertSent(StockCleared::class, function ($mail) use ($product, $admin) {
            return $mail->productName === $product->name
                && (float)$mail->previousStock === 15.0
                && $mail->newStock === 0
                && $mail->accountingType === 'operational'
                && $mail->reason === 'Batch Spoilage / Quality Issue';
        });

        // 3. Verify audit log entry exists
        $this->assertDatabaseHas('audit_logs', [
            'category' => 'Inventory',
            'action' => 'Zeroed Out Product Stock',
        ]);
    }

    public function test_admin_can_choose_write_off_accounting_mode()
    {
        Mail::fake();

        $admin = $this->createAdmin('auditor@moshedibles.com', 'Auditor Admin');
        $product = $this->createProduct('Fruit Tart', 10.0, 3000.0);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.products.clearStock'), [
            'product_id' => $product->id,
            'reason' => 'Batch Spoilage / Quality Issue',
            'accounting_type' => 'write_off',
            'confirm_deplete' => '1',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals(0, $product->fresh()->stock_on_hand);

        Mail::assertSent(StockCleared::class, function ($mail) {
            return $mail->accountingType === 'write_off';
        });
    }

    public function test_all_admins_receive_individual_stock_cleared_notifications()
    {
        Mail::fake();

        $admin1 = $this->createAdmin('admin1@moshedibles.com', 'Admin One');
        $admin2 = $this->createAdmin('admin2@moshedibles.com', 'Admin Two');
        $product = $this->createProduct('Cookie Box', 12.0);

        $response = $this->actingAs($admin1, 'admin')->post(route('admin.products.clearStock'), [
            'product_id' => $product->id,
            'reason' => 'Stock Write-off / Damaged Goods',
            'accounting_type' => 'operational',
            'confirm_deplete' => '1',
        ]);

        $response->assertSessionHasNoErrors();

        // Check both admins were notified
        Mail::assertSent(StockCleared::class, function ($mail) use ($admin1, $admin2) {
            return ($mail->hasTo($admin1->email) || $mail->hasBcc($admin1->email))
                && ($mail->hasTo($admin2->email) || $mail->hasBcc($admin2->email));
        });
    }

    public function test_admin_cannot_clear_already_zero_stock()
    {
        Mail::fake();

        $admin = $this->createAdmin();
        $product = $this->createProduct('Empty Cupcakes', 0.0, 1500.0);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.products.clearStock'), [
            'product_id' => $product->id,
            'reason' => 'Physical Stock Count Discrepancy',
            'confirm_deplete' => '1',
        ]);

        $response->assertRedirect();
        Mail::assertNothingSent();
        $this->assertEquals(0, $product->fresh()->stock_on_hand);
    }

    public function test_admin_can_execute_emergency_full_catalog_reset()
    {
        Mail::fake();

        $admin = $this->createAdmin();
        $product1 = $this->createProduct('Cake Alpha', 20.0);
        $product2 = $this->createProduct('Cake Beta', 30.0);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.products.clearAllStock'), [
            'confirmation_text' => 'CONFIRM EMERGENCY RESET',
            'reason' => 'Quarterly physical warehouse count reset',
            'notes' => 'Authorized by executive team',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        // Verify all products zeroed
        $this->assertEquals(0, $product1->fresh()->stock_on_hand);
        $this->assertEquals(0, $product2->fresh()->stock_on_hand);

        // Verify bulk notification sent
        Mail::assertSent(StockCleared::class, function ($mail) {
            return $mail->isBulk === true
                && (float)$mail->totalUnitsCleared === 50.0
                && $mail->affectedProductsCount === 2;
        });

        // Verify audit log
        $this->assertDatabaseHas('audit_logs', [
            'category' => 'Inventory',
            'action' => 'Full Catalog Stock Wipeout (Danger Zone)',
        ]);
    }

    public function test_emergency_reset_fails_without_exact_confirmation_phrase()
    {
        Mail::fake();

        $admin = $this->createAdmin();
        $product = $this->createProduct('Cake Secure', 10.0);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.products.clearAllStock'), [
            'confirmation_text' => 'wrong phrase',
            'reason' => 'Accidental trigger',
        ]);

        $response->assertSessionHasErrors(['confirmation_text']);
        $this->assertEquals(10.0, $product->fresh()->stock_on_hand);
        Mail::assertNothingSent();
    }
}

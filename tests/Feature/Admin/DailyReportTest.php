<?php

namespace Tests\Feature\Admin;

use App\Mail\Finance\DailyPerformance;
use App\Mail\Stock\LowStockAlert;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Production;
use App\Models\Sale;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DailyReportTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(string $name = 'Report Admin', string $email = 'report-admin@example.test'): Admin
    {
        return Admin::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make('secretpass123'),
        ]);
    }

    public function test_authorized_admin_can_trigger_daily_report_and_receives_performance_email()
    {
        Mail::fake();

        $admin = $this->createAdmin();

        $today = Carbon::today();

        // 1. Create a sale recorded today
        Sale::create([
            'reference_no'   => 'REC-TESTREPORT1',
            'user_id'        => $admin->id,
            'user_type'      => 'admin',
            'total_amount'   => 5000,
            'payable_amount' => 5000,
            'payment_method' => 'Cash',
            'order_status'   => 'completed',
            'payment_status' => 'paid',
            'created_at'     => $today->copy()->addHours(10),
        ]);

        // 2. Create production recorded today
        $product = Product::create([
            'name'          => 'Report Bread',
            'slug'          => 'report-bread',
            'selling_price' => 500,
            'stock_on_hand' => 50,
            'is_active'     => true,
        ]);

        Production::create([
            'product_id'       => $product->id,
            'admin_id'         => $admin->id,
            'quantity'         => 10,
            'unit_cost'        => 200,
            'total_cost'       => 2000,
            'selling_price'    => 500,
            'expected_revenue' => 5000,
            'profit'           => 3000,
            'produced_at'      => $today->copy()->addHours(8),
            'created_at'       => $today->copy()->addHours(8),
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('send.report'));
        $response->assertRedirect();

        // Verify DailyPerformance mail sent to currently authenticated admin with exact data
        Mail::assertSent(DailyPerformance::class, function ($mail) use ($admin) {
            return $mail->hasTo($admin->email)
                && (float) $mail->stats['revenue'] === 5000.0
                && (float) $mail->stats['cost'] === 2000.0
                && (float) $mail->stats['profit'] === 3000.0;
        });

        // Stock was 50 (above 10), so LowStockAlert should NOT be sent
        Mail::assertNotSent(LowStockAlert::class);
    }

    public function test_low_stock_alert_is_sent_when_active_product_stock_is_at_or_below_ten()
    {
        Mail::fake();

        $admin = $this->createAdmin();

        // Product at boundary condition: stock_on_hand <= 10 and is_active = true
        Product::create([
            'name'          => 'Low Confectionery Item',
            'slug'          => 'low-confectionery-item',
            'selling_price' => 350,
            'stock_on_hand' => 10,
            'is_active'     => true,
        ]);

        $this->actingAs($admin, 'admin')->get(route('send.report'))->assertRedirect();

        Mail::assertSent(DailyPerformance::class, function ($mail) use ($admin) {
            return $mail->hasTo($admin->email);
        });

        Mail::assertSent(LowStockAlert::class, function ($mail) use ($admin) {
            return $mail->hasTo($admin->email)
                && $mail->lowStockItems->count() === 1
                && $mail->lowStockItems->first()->name === 'Low Confectionery Item';
        });
    }

    public function test_low_stock_alert_is_not_sent_when_all_active_products_have_sufficient_stock()
    {
        Mail::fake();

        $admin = $this->createAdmin();

        // Active product with stock > 10 (e.g. 11)
        Product::create([
            'name'          => 'Healthy Stock Item',
            'slug'          => 'healthy-stock-item',
            'selling_price' => 400,
            'stock_on_hand' => 11,
            'is_active'     => true,
        ]);

        $this->actingAs($admin, 'admin')->get(route('send.report'))->assertRedirect();

        Mail::assertSent(DailyPerformance::class);
        Mail::assertNotSent(LowStockAlert::class);
    }

    public function test_low_stock_alert_ignores_inactive_products_with_low_stock()
    {
        Mail::fake();

        $admin = $this->createAdmin();

        // Inactive product with stock <= 10 must NOT trigger alert
        Product::create([
            'name'          => 'Discontinued Low Item',
            'slug'          => 'discontinued-low-item',
            'selling_price' => 200,
            'stock_on_hand' => 2,
            'is_active'     => false,
        ]);

        $this->actingAs($admin, 'admin')->get(route('send.report'))->assertRedirect();

        Mail::assertSent(DailyPerformance::class);
        Mail::assertNotSent(LowStockAlert::class);
    }

    public function test_daily_report_succeeds_when_catalog_has_no_products()
    {
        Mail::fake();

        $admin = $this->createAdmin();

        $this->actingAs($admin, 'admin')->get(route('send.report'))->assertRedirect();

        Mail::assertSent(DailyPerformance::class, function ($mail) use ($admin) {
            return $mail->hasTo($admin->email)
                && (float) $mail->stats['revenue'] === 0.0
                && (float) $mail->stats['cost'] === 0.0
                && (float) $mail->stats['profit'] === 0.0;
        });

        Mail::assertNotSent(LowStockAlert::class);
    }

    public function test_unauthorized_users_cannot_trigger_daily_report_or_trigger_emails()
    {
        Mail::fake();

        // 1. Guest attempt
        $guestResponse = $this->get(route('send.report'));
        $guestResponse->assertRedirect(route('admin.login'));

        // 2. Staff attempt
        $staff = Staff::create([
            'name'     => 'Staff Operator',
            'email'    => 'staff-op@example.test',
            'password' => Hash::make('password123'),
        ]);

        $staffResponse = $this->actingAs($staff, 'staff')->get(route('send.report'));
        $staffResponse->assertRedirect(route('admin.login'));

        Mail::assertNothingSent();
    }
}

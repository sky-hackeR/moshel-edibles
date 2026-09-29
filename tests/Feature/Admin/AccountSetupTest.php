<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Staff;
use App\Notifications\AdminResetPassword;
use App\Notifications\StaffResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AccountSetupTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_admin_and_staff_accounts_receive_reset_links_instead_of_passwords()
    {
        Mail::fake();
        Notification::fake();

        $owner = Admin::create([
            'name' => 'Account Owner',
            'email' => 'owner@example.test',
            'password' => Hash::make('owner-password'),
        ]);

        $this->actingAs($owner, 'admin')
            ->post('/admin/newAdmin', [
                'name' => 'New Admin',
                'email' => 'new-admin@example.test',
            ])
            ->assertRedirect();

        $newAdmin = Admin::where('email', 'new-admin@example.test')->firstOrFail();
        $this->assertFalse(Hash::check('owner-password', $newAdmin->password));
        Notification::assertSentTo($newAdmin, AdminResetPassword::class);

        $this->post('/admin/newStaff', [
            'name' => 'New Staff',
            'email' => 'new-staff@example.test',
        ])
            ->assertRedirect();

        $newStaff = Staff::where('email', 'new-staff@example.test')->firstOrFail();
        $this->assertFalse(Hash::check('owner-password', $newStaff->password));
        Notification::assertSentTo($newStaff, StaffResetPassword::class);
    }

    public function test_online_sales_cannot_be_voided_through_the_pos()
    {
        $owner = Admin::create([
            'name' => 'Account Owner',
            'email' => 'owner@example.test',
            'password' => Hash::make('owner-password'),
        ]);
        $customer = Customer::create([
            'name' => 'Order Customer',
            'email' => 'order@example.test',
            'password' => Hash::make('customer-password'),
        ]);
        $product = Product::create([
            'name' => 'Review Product',
            'slug' => 'review-product',
            'selling_price' => 10,
            'stock_on_hand' => 0,
            'is_active' => true,
        ]);
        $sale = Sale::create([
            'reference_no' => 'MOS-REVIEW12345',
            'user_id' => $customer->id,
            'user_type' => 'customer',
            'customer_id' => $customer->id,
            'total_amount' => 10,
            'discount_amount' => 0,
            'payable_amount' => 10,
            'payment_method' => 'Paystack',
            'order_status' => 'payment_review',
            'payment_status' => 'paid',
        ]);
        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 10,
            'subtotal' => 10,
        ]);

        $this->actingAs($owner, 'admin')
            ->post(route('sales.void'), ['sale_id' => $sale->id, 'reason' => 'Payment review'])
            ->assertRedirect();

        $this->assertDatabaseHas('sales', ['id' => $sale->id, 'deleted_at' => null]);
        $this->assertSame(0.0, (float) $product->fresh()->stock_on_hand);
        $this->assertDatabaseHas('sale_items', ['id' => $sale->items()->first()->id, 'deleted_at' => null]);
    }
}
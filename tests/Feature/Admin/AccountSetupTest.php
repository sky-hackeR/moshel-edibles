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

    public function test_admin_customer_directory_shows_initials_contact_and_confirmed_spend()
    {
        $owner = Admin::create([
            'name' => 'Account Owner',
            'email' => 'owner@example.test',
            'password' => Hash::make('owner-password'),
        ]);
        $customer = Customer::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.test',
            'password' => Hash::make('customer-password'),
            'phone' => '08012345678',
            'address' => '12 Bakery Road, Lagos',
        ]);
        Sale::create([
            'reference_no' => 'MOS-CUSTOMER123',
            'user_id' => $customer->id,
            'user_type' => 'customer',
            'customer_id' => $customer->id,
            'total_amount' => 2500,
            'shipping_fee' => 0,
            'discount_amount' => 0,
            'payable_amount' => 2500,
            'payment_method' => 'Paystack',
            'order_status' => 'processing',
            'payment_status' => 'paid',
        ]);
        Sale::create([
            'reference_no' => 'MOS-CUSTOMERPENDING',
            'user_id' => $customer->id,
            'user_type' => 'customer',
            'customer_id' => $customer->id,
            'total_amount' => 500,
            'shipping_fee' => 0,
            'discount_amount' => 0,
            'payable_amount' => 500,
            'payment_method' => 'Paystack',
            'order_status' => 'pending_payment',
            'payment_status' => 'pending',
        ]);
        Sale::create([
            'reference_no' => 'MOS-CUSTOMERFAILED',
            'user_id' => $customer->id,
            'user_type' => 'customer',
            'customer_id' => $customer->id,
            'total_amount' => 300,
            'shipping_fee' => 0,
            'discount_amount' => 0,
            'payable_amount' => 300,
            'payment_method' => 'Paystack',
            'order_status' => 'payment_failed',
            'payment_status' => 'failed',
            'delivery_address' => 'Recent order address',
            'delivery_phone' => '08099998888',
        ]);

        $this->actingAs($owner, 'admin')
            ->get('/admin/customers')
            ->assertOk()
            ->assertSee('JD')
            ->assertSee('Awaiting Verification')
            ->assertSee('Email unverified')
            ->assertSee('Inactive')
            ->assertSee('08012345678')
            ->assertSee('12 Bakery Road, Lagos')
            ->assertSee('₦2,500.00')
            ->assertSee('All orders')
            ->assertSee('Needs attention')
            ->assertSee('Latest order')
            ->assertSee('MOS-CUSTOMERFAILED')
            ->assertSee('Recent order address');
    }

    public function test_admin_can_open_only_the_selected_customers_order_history()
    {
        $owner = Admin::create([
            'name' => 'Account Owner',
            'email' => 'owner@example.test',
            'password' => Hash::make('owner-password'),
        ]);
        $customer = Customer::create([
            'name' => 'Selected Customer',
            'email' => 'selected@example.test',
            'password' => Hash::make('customer-password'),
        ]);
        $otherCustomer = Customer::create([
            'name' => 'Other Customer',
            'email' => 'other@example.test',
            'password' => Hash::make('customer-password'),
        ]);

        foreach ([
            [$customer, 'MOS-SELECTED-ORDER', 'Selected order address'],
            [$otherCustomer, 'MOS-OTHER-ORDER', 'Other order address'],
        ] as [$orderCustomer, $reference, $address]) {
            Sale::create([
                'reference_no' => $reference,
                'user_id' => $orderCustomer->id,
                'user_type' => 'customer',
                'customer_id' => $orderCustomer->id,
                'total_amount' => 100,
                'shipping_fee' => 0,
                'discount_amount' => 0,
                'payable_amount' => 100,
                'payment_method' => 'Paystack',
                'order_status' => 'processing',
                'payment_status' => 'paid',
                'delivery_address' => $address,
            ]);
        }

        $this->actingAs($owner, 'admin')
            ->get(route('customers.orders', $customer))
            ->assertOk()
            ->assertSee('MOS-SELECTED-ORDER')
            ->assertSee('Selected order address')
            ->assertDontSee('MOS-OTHER-ORDER')
            ->assertDontSee('Other order address');
    }
}
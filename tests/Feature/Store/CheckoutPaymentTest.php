<?php

namespace Tests\Feature\Store;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Notifications\CustomerOrderConfirmed;
use App\Services\Paystack\PaystackService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class CheckoutPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Notification::fake();
    }

    public function test_repeated_success_callback_only_deducts_stock_once()
    {
        [$customer, $product, $sale] = $this->createSale(1);
        $gateway = Mockery::mock(PaystackService::class);
        $gateway->shouldReceive('verify')->twice()->andReturn($this->successfulPayment($sale));
        $this->app->instance(PaystackService::class, $gateway);

        $this->get(route('store.checkout.callback', ['reference' => $sale->reference_no]))
            ->assertRedirect(route('store.checkout.success', $sale->reference_no));
        $this->get(route('store.checkout.callback', ['reference' => $sale->reference_no]))
            ->assertRedirect(route('store.checkout.success', $sale->reference_no));

        $this->assertSame(0.0, (float) $product->fresh()->stock_on_hand);
        $this->assertSame('paid', $sale->fresh()->payment_status);
        $this->assertCount(1, Notification::sent($customer, CustomerOrderConfirmed::class));
    }

    public function test_successful_charge_with_unavailable_stock_is_recorded_for_review()
    {
        [, $product, $sale] = $this->createSale(0);
        $gateway = Mockery::mock(PaystackService::class);
        $gateway->shouldReceive('verify')->once()->andReturn($this->successfulPayment($sale));
        $this->app->instance(PaystackService::class, $gateway);

        $this->get(route('store.checkout.callback', ['reference' => $sale->reference_no]))
            ->assertRedirect(route('store.checkout.failed', $sale->reference_no));

        $this->assertSame('paid', $sale->fresh()->payment_status);
        $this->assertSame('payment_review', $sale->fresh()->order_status);
        $this->assertSame(1000, $sale->fresh()->paystack_amount);
        $this->assertSame('NGN', $sale->fresh()->paystack_currency);
        $this->assertSame(0.0, (float) $product->fresh()->stock_on_hand);
    }

    public function test_successful_gateway_response_with_wrong_amount_or_currency_requires_review()
    {
        [, $product, $sale] = $this->createSale(2);
        $gateway = Mockery::mock(PaystackService::class);
        $gateway->shouldReceive('verify')->once()->andReturn([
            'status' => 'success',
            'reference' => $sale->reference_no,
            'amount' => 999,
            'currency' => 'USD',
        ]);
        $this->app->instance(PaystackService::class, $gateway);

        $this->get(route('store.checkout.callback', ['reference' => $sale->reference_no]))
            ->assertRedirect(route('store.checkout.failed', $sale->reference_no));

        $this->assertSame('review', $sale->fresh()->payment_status);
        $this->assertSame('payment_review', $sale->fresh()->order_status);
        $this->assertSame(999, $sale->fresh()->paystack_amount);
        $this->assertSame('USD', $sale->fresh()->paystack_currency);
        $this->assertSame(2.0, (float) $product->fresh()->stock_on_hand);
    }

    public function test_gateway_timeout_leaves_payment_pending()
    {
        [$customer, , $sale] = $this->createSale(1);
        $gateway = Mockery::mock(PaystackService::class);
        $gateway->shouldReceive('verify')->once()->andThrow(new RuntimeException('Gateway timeout'));
        $this->app->instance(PaystackService::class, $gateway);

        $this->get(route('store.checkout.callback', ['reference' => $sale->reference_no]))
            ->assertRedirect(route('store.checkout.failed', $sale->reference_no));

        $this->assertSame('pending', $sale->fresh()->payment_status);
        $this->actingAs($customer, 'customer');
        $this->get(route('store.checkout.failed', $sale->reference_no))
            ->assertOk()
            ->assertSee('Payment Is Being Confirmed')
            ->assertSee('Check Payment Again');
        $this->get(route('store.checkout.success', $sale->reference_no))
            ->assertRedirect(route('store.checkout.failed', $sale->reference_no));
        Notification::assertNothingSent();
    }

    public function test_customer_can_requery_a_legacy_failed_order_without_deducting_stock_twice()
    {
        [$customer, $product, $sale] = $this->createSale(1);
        $product->decrement('stock_on_hand', 1);
        $sale->update([
            'payment_status' => 'failed',
            'order_status' => 'payment_failed',
            'paid_at' => now(),
        ]);

        $gateway = Mockery::mock(PaystackService::class);
        $gateway->shouldReceive('verify')->once()->with($sale->reference_no)->andReturn($this->successfulPayment($sale));
        $this->app->instance(PaystackService::class, $gateway);

        $this->actingAs($customer, 'customer')
            ->post(route('customer.account.orders.requery', $sale->reference_no))
            ->assertRedirect(route('customer.account.orders.show', $sale->reference_no));

        $this->assertSame('paid', $sale->fresh()->payment_status);
        $this->assertSame('processing', $sale->fresh()->order_status);
        $this->assertNotNull($sale->fresh()->inventory_deducted_at);
        $this->assertSame(0.0, (float) $product->fresh()->stock_on_hand);
    }

    public function test_customer_can_open_order_details_but_cannot_open_another_customers_order()
    {
        [$customer, , $sale] = $this->createSale(1);
        $otherCustomer = Customer::create([
            'name' => 'Other Customer',
            'email' => 'other@example.test',
            'password' => bcrypt('test-password'),
        ]);

        $this->actingAs($customer, 'customer')
            ->get(route('customer.account.orders.show', $sale->reference_no))
            ->assertOk()
            ->assertSee($sale->reference_no)
            ->assertSee('Payment summary')
            ->assertSee('Shipping');

        $this->actingAs($otherCustomer, 'customer')
            ->get(route('customer.account.orders.show', $sale->reference_no))
            ->assertNotFound();
    }

    public function test_customer_checkout_displays_the_configured_shipping_fee()
    {
        $customer = Customer::create([
            'name' => 'Checkout Customer',
            'email' => 'checkout-view@example.test',
            'password' => bcrypt('test-password'),
        ]);
        $product = Product::create([
            'name' => 'Shipping View Product',
            'slug' => 'shipping-view-product',
            'selling_price' => 10,
            'stock_on_hand' => 2,
            'is_active' => true,
        ]);
        config(['services.store.shipping_fee' => 1500]);

        $this->actingAs($customer, 'customer')
            ->withSession(['store_cart' => [$product->id => 1]])
            ->get(route('store.checkout'))
            ->assertOk()
            ->assertSee('Flat shipping')
            ->assertSee('₦1,500.00')
            ->assertSee('₦1,510.00');
    }

    public function test_shipping_fee_is_added_to_the_provider_amount_and_order_total()
    {
        $customer = Customer::create([
            'name' => 'Checkout Customer',
            'email' => 'checkout@example.test',
            'password' => bcrypt('test-password'),
        ]);
        $product = Product::create([
            'name' => 'Checkout Product',
            'slug' => 'checkout-product',
            'selling_price' => 10,
            'stock_on_hand' => 2,
            'is_active' => true,
        ]);
        config(['services.store.shipping_fee' => 1500]);
        session(['store_cart' => [$product->id => 1]]);

        $gateway = Mockery::mock(PaystackService::class);
        $gateway->shouldReceive('initialize')
            ->once()
            ->with('checkout@example.test', 1510.0, Mockery::type('string'), Mockery::type('string'))
            ->andReturn([
                'authorization_url' => 'https://paystack.example/authorize',
                'reference' => 'MOS-PAYSTACK123',
            ]);
        $this->app->instance(PaystackService::class, $gateway);

        $this->actingAs($customer, 'customer')
            ->post(route('store.checkout.initialize'), [
                'delivery_address' => '1 Test Street, Lagos',
                'delivery_phone' => '08000000000',
            ])
            ->assertRedirect('https://paystack.example/authorize');

        $sale = Sale::where('customer_id', $customer->id)->firstOrFail();
        $this->assertSame(10.0, (float) $sale->total_amount);
        $this->assertSame(1500.0, (float) $sale->shipping_fee);
        $this->assertSame(1510.0, (float) $sale->payable_amount);
    }

    private function createSale(int $stock): array
    {
        $customer = Customer::create([
            'name' => 'Test Customer',
            'email' => 'customer@example.test',
            'password' => bcrypt('test-password'),
        ]);
        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'selling_price' => 10,
            'stock_on_hand' => $stock,
            'is_active' => true,
        ]);
        $sale = Sale::create([
            'reference_no' => 'MOS-' . strtoupper(bin2hex(random_bytes(6))),
            'user_id' => $customer->id,
            'user_type' => 'customer',
            'customer_id' => $customer->id,
            'total_amount' => 10,
            'discount_amount' => 0,
            'payable_amount' => 10,
            'payment_method' => 'Paystack',
            'order_status' => 'pending_payment',
            'payment_status' => 'pending',
            'delivery_address' => 'Test Address',
            'delivery_phone' => '08000000000',
            'paystack_reference' => null,
        ]);
        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 10,
            'subtotal' => 10,
        ]);

        return [$customer, $product, $sale];
    }

    private function successfulPayment(Sale $sale): array
    {
        return [
            'status' => 'success',
            'reference' => $sale->reference_no,
            'amount' => 1000,
            'currency' => 'NGN',
        ];
    }
}
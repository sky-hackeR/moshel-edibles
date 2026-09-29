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
<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Mail\Store\OrderReceived;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Notifications\CustomerPaymentFailed;
use App\Notifications\CustomerOrderConfirmed;
use App\Services\Paystack\PaystackService;
use App\Services\Store\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use RuntimeException;

class CheckoutController extends Controller
{
    public function index(CartService $cart)
    {
        if (!auth('customer')->check()) {
            return redirect()->route('store.cart')->with('auth_modal', 'login');
        }

        if ($cart->items()->isEmpty()) {
            return redirect()->route('store.cart')->with('info', 'Your basket is currently empty. Please select treats to continue.');
        }

        $customer = auth('customer')->user();
        $pastAddresses = Sale::where('customer_id', $customer->id)
            ->whereNotNull('delivery_address')
            ->where('delivery_address', '!=', '')
            ->latest()
            ->get(['delivery_address', 'delivery_phone', 'created_at'])
            ->unique('delivery_address')
            ->values();

        $lastOrder = Sale::where('customer_id', $customer->id)
            ->whereNotNull('delivery_address')
            ->latest()
            ->first();

        $defaultAddress = $customer->address ?: ($lastOrder?->delivery_address ?? '');
        $defaultPhone = $customer->phone ?: ($lastOrder?->delivery_phone ?? '');

        return view('store.checkout.index', [
            'items' => $cart->items(),
            'total' => $cart->total(),
            'customer' => $customer,
            'defaultAddress' => $defaultAddress,
            'defaultPhone' => $defaultPhone,
            'pastAddresses' => $pastAddresses,
        ]);
    }

    public function initialize(Request $request, CartService $cart, PaystackService $paystack)
    {
        $data = $request->validate([
            'delivery_address' => 'required|string|max:1000',
            'delivery_phone' => 'required|string|max:30',
            'save_address' => 'nullable',
        ]);
        $items = $cart->items();
        abort_if($items->isEmpty(), 422, 'Your cart is empty.');
        $total = (float) $items->sum('subtotal');

        $customer = auth('customer')->user();
        if ($customer) {
            // Persist address and phone to customer record if empty or if save_address option is checked
            if (empty($customer->address) || empty($customer->phone) || $request->has('save_address')) {
                $customer->update([
                    'address' => $data['delivery_address'],
                    'phone' => $data['delivery_phone'],
                ]);
            }
        }

        $sale = DB::transaction(function () use ($items, $total, $data) {
            $reference = 'MOS-' . strtoupper(Str::random(12));
            $sale = Sale::create([
                'reference_no' => $reference,
                'user_id' => auth('customer')->id(),
                'user_type' => 'customer',
                'customer_id' => auth('customer')->id(),
                'total_amount' => $total,
                'discount_amount' => 0,
                'payable_amount' => $total,
                'payment_method' => 'Paystack',
                'order_status' => 'pending_payment',
                'payment_status' => 'pending',
                'delivery_address' => $data['delivery_address'],
                'delivery_phone' => $data['delivery_phone'],
            ]);

            foreach ($items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            return $sale;
        });

        try {
            $payment = $paystack->initialize(
                auth('customer')->user()->email,
                (float) $sale->payable_amount,
                $sale->reference_no,
                route('store.checkout.callback')
            );
            $sale->update(['paystack_reference' => $payment['reference'] ?? $sale->reference_no]);
            return redirect()->away($payment['authorization_url']);
        } catch (\Throwable $exception) {
            report($exception);
            $sale->update(['order_status' => 'payment_failed', 'payment_status' => 'failed']);
            $this->notifySafely(function () use ($sale) {
                $this->notifyCustomer($sale, new CustomerPaymentFailed($sale));
            });
            return redirect()->route('store.checkout')->with('error', 'Payment could not be started. Please try again.');
        }
    }

    public function callback(Request $request, PaystackService $paystack)
    {
        $reference = $request->query('reference', $request->query('trxref'));
        abort_unless($reference, 404);
        $sale = Sale::where('reference_no', $reference)->orWhere('paystack_reference', $reference)->firstOrFail();

        try {
            $payment = $paystack->verify($reference);
            $outcome = $this->processVerifiedPayment($sale, $payment);
            if (in_array($outcome, ['paid', 'review'], true)) {
                app(CartService::class)->clear();
            }

            return $outcome === 'paid'
                ? redirect()->route('store.checkout.success', $sale->reference_no)
                : redirect()->route('store.checkout.failed', $sale->reference_no);
        } catch (\Throwable $exception) {
            report($exception);
            return redirect()->route('store.checkout.failed', $sale->reference_no);
        }
    }

    public function webhook(Request $request, PaystackService $paystack)
    {
        $signature = $request->header('x-paystack-signature');
        $secret = config('services.paystack.secret_key');
        abort_unless(is_string($secret) && $secret !== '', 503);

        $expected = hash_hmac('sha512', $request->getContent(), $secret);
        abort_unless($signature && hash_equals($expected, $signature), 401);

        $payload = $request->all();
        if (($payload['event'] ?? null) === 'charge.success') {
            $reference = $payload['data']['reference'] ?? null;
            $sale = Sale::where('reference_no', $reference)->orWhere('paystack_reference', $reference)->first();
            if ($sale) {
                try {
                    $outcome = $this->processVerifiedPayment($sale, $paystack->verify($reference));
                    if ($outcome === 'pending') {
                        return response()->json(['status' => false], 422);
                    }
                } catch (\Throwable $exception) {
                    report($exception);
                    return response()->json(['status' => false], 422);
                }
            }
        }

        return response()->json(['status' => true]);
    }

    public function success(string $reference)
    {
        $sale = Sale::with('items.product')->where('reference_no', $reference)->firstOrFail();
        if ($sale->payment_status !== 'paid' || $sale->order_status === 'payment_review') {
            return redirect()->route('store.checkout.failed', $sale->reference_no);
        }

        return view('store.checkout.success', ['sale' => $sale]);
    }

    public function failed(string $reference)
    {
        return view('store.checkout.failed', ['sale' => Sale::where('reference_no', $reference)->firstOrFail()]);
    }

    private function processVerifiedPayment(Sale $sale, array $payment): string
    {
        $status = $payment['status'] ?? null;
        if (in_array($status, ['failed', 'abandoned'], true)) {
            $this->markPaymentFailed($sale);
            return 'failed';
        }

        if ($status !== 'success') {
            return 'pending';
        }

        $verifiedReference = (string) ($payment['reference'] ?? '');
        $gatewayAmount = filter_var($payment['amount'] ?? null, FILTER_VALIDATE_INT);
        if ($gatewayAmount !== false && $gatewayAmount < 0) {
            $gatewayAmount = false;
        }
        $gatewayCurrency = strtoupper((string) ($payment['currency'] ?? ''));
        $result = DB::transaction(function () use ($sale, $verifiedReference, $gatewayAmount, $gatewayCurrency) {
            $lockedSale = Sale::with('items')->lockForUpdate()->findOrFail($sale->id);
            if (in_array($lockedSale->payment_status, ['paid', 'review'], true)) {
                return [
                    'outcome' => $lockedSale->order_status === 'payment_review' ? 'review' : 'paid',
                    'newly_processed' => false,
                ];
            }

            $expectedReference = $lockedSale->paystack_reference ?: $lockedSale->reference_no;
            $referenceMatches = $verifiedReference !== '' && hash_equals($expectedReference, $verifiedReference);
            $amountMatches = $gatewayAmount !== false
                && $gatewayAmount === (int) round((float) $lockedSale->payable_amount * 100);
            $currencyMatches = $gatewayCurrency === 'NGN';
            $paymentMatchesOrder = $referenceMatches && $amountMatches && $currencyMatches;
            $products = [];
            $stockAvailable = $paymentMatchesOrder;

            foreach ($lockedSale->items as $item) {
                $product = $item->product()->lockForUpdate()->first();
                if (!$product || !$product->is_active || $product->stock_on_hand < $item->quantity) {
                    $stockAvailable = false;
                    break;
                }
                $products[] = [$product, $item->quantity];
            }

            if ($stockAvailable) {
                foreach ($products as [$product, $quantity]) {
                    $product->decrement('stock_on_hand', $quantity);
                }
            }

            $requiresReview = !$paymentMatchesOrder || !$stockAvailable;
            $lockedSale->update([
                'payment_status' => $paymentMatchesOrder ? 'paid' : 'review',
                'order_status' => $requiresReview ? 'payment_review' : 'processing',
                'paid_at' => now(),
                'paystack_reference' => $referenceMatches ? $verifiedReference : $lockedSale->paystack_reference,
                'paystack_amount' => $gatewayAmount === false ? null : $gatewayAmount,
                'paystack_currency' => $gatewayCurrency ?: null,
            ]);

            return [
                'outcome' => $requiresReview ? 'review' : 'paid',
                'newly_processed' => true,
            ];
        });

        if ($result['newly_processed']) {
            $sale->refresh()->load('customer', 'items.product');
            $this->notifySafely(function () use ($sale) {
                $this->notifyCustomer($sale, new CustomerOrderConfirmed($sale));
            });

            $adminEmail = config('services.paystack.admin_email', config('mail.from.address'));
            if ($adminEmail) {
                $this->notifySafely(function () use ($adminEmail, $sale) {
                    Mail::to($adminEmail)->send(new OrderReceived($sale));
                });
            }
        }

        return $result['outcome'];
    }

    private function markPaymentFailed(Sale $sale): void
    {
        $wasMarkedFailed = DB::transaction(function () use ($sale) {
            $lockedSale = Sale::lockForUpdate()->findOrFail($sale->id);
            if (in_array($lockedSale->payment_status, ['paid', 'review'], true)) {
                return false;
            }

            $lockedSale->update(['order_status' => 'payment_failed', 'payment_status' => 'failed']);
            return true;
        });

        if ($wasMarkedFailed) {
            $sale->refresh();
            $this->notifySafely(function () use ($sale) {
                $this->notifyCustomer($sale, new CustomerPaymentFailed($sale));
            });
        }
    }

    private function notifyCustomer(Sale $sale, $notification): void
    {
        if ($sale->customer) {
            $sale->customer->notify($notification);
        }
    }

    private function notifySafely(callable $callback): void
    {
        try {
            $callback();
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
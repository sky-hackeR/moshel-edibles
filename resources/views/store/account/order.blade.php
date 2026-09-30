@extends('store.layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="page-header bg-section">
    <div class="container">
        <div class="page-header-box">
            <h1>Order Details</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('store.welcome') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customer.account') }}">My Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $order->reference_no }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section class="store-account-section bg-section">
    <div class="container">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="store-account-heading">
            <div>
                <span class="store-summary-kicker">Order placed {{ $order->created_at->format('d M Y, h:i A') }}</span>
                <h2>#{{ $order->reference_no }}</h2>
                <p class="mb-0">{{ $order->items->sum('quantity') }} items · NGN {{ number_format($order->payable_amount, 2) }}</p>
            </div>
            <a href="{{ route('customer.account') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> All orders
            </a>
        </div>

        <div class="store-account-panel">
            <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between border-bottom pb-3 mb-4">
                <div>
                    <span class="store-summary-kicker">Payment</span>
                    <span class="store-status store-status-{{ $order->payment_status }}">{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</span>
                </div>
                <div>
                    <span class="store-summary-kicker">Order status</span>
                    <strong>{{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</strong>
                </div>
                @if(in_array($order->payment_status, ['pending', 'failed'], true))
                    <form method="POST" action="{{ route('customer.account.orders.requery', $order->reference_no) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fa-solid fa-rotate me-1"></i> Check Paystack status
                        </button>
                    </form>
                @endif
            </div>

            <h3 class="h5 mb-3">Items</h3>
            <div class="table-responsive mb-4">
                <table class="table align-middle">
                    <thead><tr><th>Product</th><th class="text-end">Qty</th><th class="text-end">Unit price</th><th class="text-end">Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ optional(optional($item->product)->storeProduct)->store_title ?? optional($item->product)->name ?? 'Unavailable product' }}</td>
                                <td class="text-end">{{ $item->quantity }}</td>
                                <td class="text-end">NGN {{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end">NGN {{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row g-4 border-top pt-4">
                <div class="col-md-6">
                    <h3 class="h5">Delivery</h3>
                    <p class="mb-1">{{ $order->delivery_address ?: 'No delivery address recorded' }}</p>
                    <p class="text-muted mb-0">{{ $order->delivery_phone }}</p>
                </div>
                <div class="col-md-6">
                    <h3 class="h5">Payment summary</h3>
                    <div class="d-flex justify-content-between py-1"><span>Items</span><span>NGN {{ number_format($order->total_amount, 2) }}</span></div>
                    <div class="d-flex justify-content-between py-1"><span>Shipping</span><span>NGN {{ number_format($order->shipping_fee, 2) }}</span></div>
                    @if($order->discount_amount > 0)
                        <div class="d-flex justify-content-between py-1"><span>Discount</span><span>- NGN {{ number_format($order->discount_amount, 2) }}</span></div>
                    @endif
                    <div class="d-flex justify-content-between border-top mt-2 pt-2 fw-bold"><span>Total</span><span>NGN {{ number_format($order->payable_amount, 2) }}</span></div>
                    @if($order->paystack_reference)
                        <small class="d-block text-muted mt-3">Paystack reference: {{ $order->paystack_reference }}</small>
                    @endif
                    @if($order->paystack_amount !== null)
                        <small class="d-block text-muted">Verified transaction: {{ $order->paystack_currency }} {{ number_format($order->paystack_amount / 100, 2) }}</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
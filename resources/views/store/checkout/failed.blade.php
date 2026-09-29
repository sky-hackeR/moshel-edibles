@extends('store.layouts.app')

@section('title', 'Payment Status')

@section('content')
<div class="page-header bg-section">
    <div class="container">
        <div class="page-header-box">
            <h1>Payment Status</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('store.welcome') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('store.cart') }}">Cart</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Payment Status</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section class="store-commerce-section bg-section py-5">
    <div class="container">
        <div class="store-empty-panel store-failure-panel mx-auto" style="max-width: 650px;">
            <div class="{{ $sale->payment_status === 'pending' ? 'store-failure-icon' : 'store-success-icon' }}">
                <i class="fa-solid {{ $sale->payment_status === 'pending' ? 'fa-clock' : 'fa-check-circle' }}"></i>
            </div>

            <span class="store-summary-kicker">Order #{{ $sale->reference_no }}</span>
            @if($sale->payment_status === 'pending')
                <h2>Payment Is Being Confirmed</h2>
                <p class="text-muted" style="font-size: 15px;">We have not received a final confirmation yet. Your order remains pending; check again before retrying.</p>
            @elseif($sale->payment_status === 'review')
                <h2>Payment Requires Review</h2>
                <p class="text-muted" style="font-size: 15px;">Paystack reported a successful transaction, but its amount or currency did not match this order. Please do not pay again while we review it.</p>
            @elseif($sale->order_status === 'payment_review')
                <h2>Payment Received, Order Under Review</h2>
                <p class="text-muted" style="font-size: 15px;">Your payment was received, but an item is no longer available. We are reviewing the order and will contact you. Please do not pay again.</p>
            @elseif($sale->payment_status === 'paid')
                <h2>Payment Confirmed</h2>
                <p class="text-muted" style="font-size: 15px;">Your payment is confirmed. Open your account to view the latest order status.</p>
            @else
                <h2>Payment Was Not Completed</h2>
                <p class="text-muted" style="font-size: 15px;">Paystack reported that this payment was not completed. If your account was charged, contact us with this order reference before trying again.</p>
            @endif

            <div class="store-success-actions mt-4">
                @if($sale->payment_status === 'pending')
                    <a href="{{ route('store.checkout.callback', ['reference' => $sale->paystack_reference ?: $sale->reference_no]) }}" class="btn-default btn-highlighted py-3 px-4">
                        <i class="fa-solid fa-rotate me-1"></i> Check Payment Again
                    </a>
                @elseif($sale->payment_status === 'paid' || $sale->payment_status === 'review')
                    <a href="{{ route('customer.account') }}" class="btn-default btn-highlighted py-3 px-4">
                        <i class="fa-solid fa-receipt me-1"></i> View My Orders
                    </a>
                @else
                    <a href="{{ route('store.checkout') }}" class="btn-default btn-highlighted py-3 px-4">
                        <i class="fa-solid fa-arrow-rotate-right me-1"></i> Retry Checkout
                    </a>
                    <a href="{{ route('store.cart') }}" class="btn-default py-3 px-4">
                        <i class="fa-solid fa-basket-shopping me-1"></i> Return to Basket
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

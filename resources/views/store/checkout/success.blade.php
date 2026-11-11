@extends('store.layouts.app')

@section('title', 'Order Confirmed - Thank You')

@section('content')
<div class="page-header bg-section">
    <div class="container">
        <div class="page-header-box">
            <h1>Order Confirmed!</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('store.welcome') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customer.account') }}">Account</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Confirmation</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section class="store-commerce-section bg-section py-5">
    <div class="container">
        <div class="store-empty-panel store-success-panel mx-auto" style="max-width: 720px;">
            <div class="store-success-icon">
                <i class="fa-solid fa-check"></i>
            </div>
            
            <span class="store-summary-kicker">Payment Successful</span>
            <h2>Thank You for Ordering with Moshel!</h2>
            <p class="text-muted" style="font-size: 15px;">
                Your payment for order <strong class="text-dark">#{{ $sale->reference_no }}</strong> was successfully confirmed. Our kitchen team has received your order and will begin freshly packaging your bakes for dispatch.
            </p>

            <div class="p-3 my-4 rounded-3 text-start bg-light border">
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <strong style="font-size: 14px;">Order Summary</strong>
                    <span class="badge bg-success">Paid</span>
                </div>

                @foreach($sale->items as $item)
                    <div class="d-flex justify-content-between align-items-center py-1 text-muted" style="font-size: 13px;">
                        <span>{{ optional(optional($item->product)->storeProduct)->store_title ?? optional($item->product)->name ?? 'Unavailable product' }}</span>
                        <strong>₦{{ number_format($item->subtotal, 2) }}</strong>
                    </div>
                @endforeach

                <div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top">
                    <strong style="font-size: 14px;">Shipping:</strong>
                    <strong style="font-size: 14px;">₦{{ number_format($sale->shipping_fee, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top">
                    <strong style="font-size: 15px; color: var(--primary-color);">Total Paid:</strong>
                    <strong style="font-size: 16px; color: var(--accent-color);">₦{{ number_format($sale->payable_amount, 2) }}</strong>
                </div>

                @if($sale->delivery_address)
                    <div class="pt-2 mt-2 border-top text-muted" style="font-size: 12px;">
                        <i class="fa-solid fa-location-dot me-1 text-accent"></i> <strong>Delivery to:</strong> {{ $sale->delivery_address }}
                        @if($sale->delivery_phone) · <i class="fa-solid fa-phone ms-2 me-1 text-accent"></i> {{ $sale->delivery_phone }} @endif
                    </div>
                @endif
            </div>

            <div class="store-success-actions">
                <a href="{{ route('customer.account') }}" class="btn-default btn-highlighted py-3 px-4">
                    <i class="fa-solid fa-receipt me-1"></i> View in My Orders
                </a>
                <a href="{{ route('store.products') }}" class="btn-default py-3 px-4">
                    <i class="fa-solid fa-bag-shopping me-1"></i> Continue Shopping
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

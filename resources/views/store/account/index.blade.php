@extends('store.layouts.app')

@section('title', 'My Account & Orders')

@section('content')
<div class="page-header bg-section">
    <div class="container">
        <div class="page-header-box">
            <h1>My Account</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('store.welcome') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">My Orders</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section class="store-commerce-section bg-section">
    <div class="container">
        <!-- Account User Header -->
        <div class="store-account-heading">
            <div>
                <span class="store-summary-kicker">Welcome Back</span>
                <h2>{{ auth('customer')->user()->name }}</h2>
                <p><i class="fa-regular fa-envelope me-1"></i> {{ auth('customer')->user()->email }} @if(auth('customer')->user()->phone) · <i class="fa-solid fa-phone ms-2 me-1"></i> {{ auth('customer')->user()->phone }} @endif</p>
            </div>
            <form method="POST" action="{{ route('customer.logout') }}">
                @csrf
                <button type="submit" class="store-logout-link">
                    <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Sign out
                </button>
            </form>
        </div>

        <!-- Navigation Tabs -->
        {{-- <div class="store-account-nav">
            <a class="active" href="{{ route('customer.account') }}"><i class="fa-solid fa-receipt"></i> Orders & History</a>
            <a href="{{ route('customer.account.profile') }}"><i class="fa-regular fa-user"></i> Profile Details</a>
            <a href="{{ route('customer.account.addresses') }}"><i class="fa-solid fa-location-dot"></i> Saved Addresses</a>
        </div> --}}

        <!-- Panel -->
        <div class="store-account-panel">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div class="section-title mb-0">
                    <h2>Order History</h2>
                    <p class="mb-0">Track and review all your past and active orders from Moshel Edibles.</p>
                </div>
                <a href="{{ route('store.products') }}" class="btn-default btn-highlighted py-2 px-3" style="font-size: 13px;">
                    <i class="fa-solid fa-plus me-1"></i> New Order
                </a>
            </div>

            @forelse($orders as $order)
                <article class="store-order-row">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <strong style="font-size: 15px; color: var(--primary-color);">Order #{{ $order->reference_no }}</strong>
                            @if($order->paystack_reference && $order->paystack_reference !== $order->reference_no)
                                <small class="text-muted" style="font-size: 11px;">(Ref: {{ $order->paystack_reference }})</small>
                            @endif
                        </div>
                        <span>
                            <i class="fa-regular fa-calendar me-1"></i> {{ $order->created_at->format('d M, Y \a\t h:i A') }} · 
                            <strong>{{ $order->items->sum('quantity') }} items</strong>
                            @if($order->delivery_address)
                                · <i class="fa-solid fa-location-dot ms-1 me-1 text-muted"></i> {{ \Illuminate\Support\Str::limit($order->delivery_address, 40) }}
                            @endif
                        </span>
                    </div>
                    <div class="store-order-status">
                        <span class="store-status store-status-{{ $order->payment_status }}">
                            <i class="fa-solid fa-circle-dot me-1" style="font-size: 8px;"></i>
                            {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                        </span>
                        <strong class="mt-1" style="font-size: 16px; color: var(--accent-color);">₦{{ number_format($order->payable_amount, 2) }}</strong>
                    </div>
                </article>
            @empty
                <div class="store-account-empty">
                    <i class="fa-solid fa-receipt"></i>
                    <h3>No orders placed yet</h3>
                    <p>Your freshly baked treats will appear here once you place your first order.</p>
                    <a href="{{ route('store.products') }}" class="btn-default btn-highlighted mt-2">Browse Fresh Menu</a>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

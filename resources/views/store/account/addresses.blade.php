@extends('store.layouts.app')
@section('title', 'Addresses')
@section('content')
<div class="page-header bg-section">
    <div class="container">
        <div class="page-header-box">
            <h1>Delivery Addresses</h1>
            {{-- <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('store.welcome') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customer.account') }}">Account</a></li>
                    <li class="breadcrumb-item active">Addresses</li>
                </ol>
            </nav> --}}
        </div>
    </div>
</div>

<section class="store-commerce-section bg-section">
    <div class="container">
        <div class="store-account-nav">
            <a href="{{ route('customer.account') }}"><i class="fa-solid fa-receipt"></i> Orders</a>
            <a href="{{ route('customer.account.profile') }}"><i class="fa-regular fa-user"></i> Profile</a>
            <a class="active" href="{{ route('customer.account.addresses') }}"><i class="fa-solid fa-location-dot"></i> Saved Addresses</a>
        </div>

        <div class="store-account-panel">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div class="section-title mb-0">
                    <h2>Saved Delivery Addresses</h2>
                    <p class="mb-0">Manage your default and recent delivery addresses used for doorstep fulfillment.</p>
                </div>
                <a href="{{ route('customer.account.profile') }}" class="btn-default btn-highlighted">
                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit Default Address
                </a>
            </div>

            @if(!empty($customer->address))
                <div class="mb-4">
                    <h4 class="mb-3" style="font-size: 15px; font-weight: 700; color: var(--accent-color); text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-star text-warning me-1"></i> Default Delivery Address
                    </h4>
                    <article class="store-address-card store-address-card-default">
                        <div class="store-address-icon-box">
                            <i class="fa-solid fa-house-chimney"></i>
                        </div>
                        <div class="store-address-content">
                            <div class="store-address-content-header">
                                <h3 class="store-address-title">{{ $customer->address }}</h3>
                                <span class="store-address-badge">
                                    <i class="fa-solid fa-check" style="font-size: 9px;"></i> Default
                                </span>
                            </div>
                            <div class="store-address-meta">
                                <span><i class="fa-solid fa-phone"></i> {{ $customer->phone ?: 'No phone recorded' }}</span>
                            </div>
                        </div>
                    </article>
                </div>
            @endif

            <h4 class="mb-3 mt-4" style="font-size: 15px; font-weight: 700; color: var(--primary-color); text-transform: uppercase; letter-spacing: 0.5px;">
                <i class="fa-solid fa-clock-rotate-left me-1"></i> Addresses from Past Orders
            </h4>

            @php
                $orderAddresses = $pastAddresses->filter(function($item) use ($customer) {
                    return empty($customer->address) || trim($item->delivery_address) !== trim($customer->address);
                });
            @endphp

            @forelse($orderAddresses as $address)
                <article class="store-address-card">
                    <div class="store-address-icon-box muted">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div class="store-address-content">
                        <div class="store-address-content-header">
                            <h3 class="store-address-title">{{ $address->delivery_address }}</h3>
                        </div>
                        <div class="store-address-meta">
                            <span><i class="fa-solid fa-phone"></i> {{ $address->delivery_phone }}</span>
                            <span class="ms-2">· Used {{ $address->created_at ? $address->created_at->diffForHumans() : 'previously' }}</span>
                        </div>
                    </div>
                </article>
            @empty
                @if(empty($customer->address))
                    <div class="store-account-empty">
                        <i class="fa-solid fa-location-dot"></i>
                        <p>No delivery addresses recorded yet. You can <a href="{{ route('customer.account.profile') }}" class="store-text-link">set your default address in profile</a> or it will be saved on your first order.</p>
                    </div>
                @else
                    <p class="text-muted" style="font-size: 14px;">No other separate past order addresses.</p>
                @endif
            @endforelse
        </div>
    </div>
</section>
@endsection

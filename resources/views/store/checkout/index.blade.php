@extends('store.layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="page-header bg-section">
    <div class="container">
        <div class="page-header-box">
            <h1>Checkout</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('store.welcome') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('store.cart') }}">Cart</a></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section class="store-commerce-section bg-section py-5">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-7">
                <div class="store-form-panel">
                    <div class="section-title mb-4">
                        <span class="store-summary-kicker">Almost there</span>
                        <h2>Delivery & Contact Details</h2>
                        <p>Your freshly baked items will be prepared and delivered promptly upon payment.</p>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger mb-4">
                            <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    @if(isset($errors) && $errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Quick Address Selector for Returning Customers --}}
                    @if(isset($pastAddresses) && $pastAddresses->count() > 0)
                        <div class="mb-4 p-3 rounded" style="background: rgba(139, 94, 60, 0.06); border: 1px dashed var(--accent-color);">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fa-solid fa-bookmark me-2" style="color: var(--accent-color);"></i>
                                <strong style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--primary-color);">Saved Addresses</strong>
                            </div>
                            <p class="mb-2 text-muted" style="font-size: 13px;">Click any address below to automatically fill the form:</p>
                            <div class="d-flex flex-wrap gap-2">
                                @if(!empty($customer->address))
                                    <button type="button" class="btn btn-sm btn-outline-dark address-picker-btn active-picker"
                                            data-address="{{ e($customer->address) }}" 
                                            data-phone="{{ e($customer->phone ?? '') }}"
                                            style="font-size: 12px; border-radius: 8px; text-align: left;">
                                        <i class="fa-solid fa-house-chimney me-1 text-success"></i> Default: {{ \Illuminate\Support\Str::limit($customer->address, 35) }}
                                    </button>
                                @endif
                                @foreach($pastAddresses as $idx => $pAddr)
                                    @if(trim($pAddr->delivery_address) !== trim($customer->address ?? ''))
                                        <button type="button" class="btn btn-sm btn-outline-secondary address-picker-btn"
                                                data-address="{{ e($pAddr->delivery_address) }}" 
                                                data-phone="{{ e($pAddr->delivery_phone ?? '') }}"
                                                style="font-size: 12px; border-radius: 8px; text-align: left;">
                                            <i class="fa-solid fa-location-dot me-1"></i> {{ \Illuminate\Support\Str::limit($pAddr->delivery_address, 35) }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('store.checkout.initialize') }}" id="checkoutForm">
                        @csrf

                        <div class="store-form-field mb-3">
                            <label for="delivery_address" class="form-label font-weight-bold">
                                Delivery Address <span class="text-danger">*</span>
                            </label>
                            <textarea id="delivery_address" name="delivery_address" rows="3" class="form-control" required placeholder="Street address, house number, area / landmark, and city...">{{ old('delivery_address', $defaultAddress ?? '') }}</textarea>
                            @error('delivery_address')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="store-form-field mb-3">
                            <label for="delivery_phone" class="form-label font-weight-bold">
                                Delivery Phone Number <span class="text-danger">*</span>
                            </label>
                            <input id="delivery_phone" type="tel" name="delivery_phone" class="form-control" value="{{ old('delivery_phone', $defaultPhone ?? '') }}" required placeholder="e.g. +234 800 000 0000">
                            <small class="text-muted">We will use this phone number for order updates and dispatch rider coordination.</small>
                            @error('delivery_phone')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-check mb-4 mt-3">
                            <input class="form-check-input" type="checkbox" name="save_address" value="1" id="save_address" checked>
                            <label class="form-check-label" for="save_address" style="font-size: 13px; color: var(--text-color);">
                                Remember and save this address to my profile for faster future orders
                            </label>
                        </div>

                        <button class="btn-default btn-highlighted w-100 py-3" type="submit" style="font-size: 16px; font-weight: 600;">
                            <i class="fa-solid fa-lock me-2"></i> Proceed to Secure Payment (₦{{ number_format($total, 2) }})
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <aside class="store-summary-panel p-4 rounded-3 shadow-sm bg-white border">
                    <span class="store-summary-kicker text-uppercase font-weight-bold" style="color: var(--accent-color); font-size: 12px; letter-spacing: 1px;">Order Overview</span>
                    <h2 class="mb-3" style="font-size: 22px;">Selected Bakes</h2>

                    <div class="store-summary-items border-top pt-3">
                        @foreach($items as $item)
                            @php
                                $storeProd = $item['product']->storeProduct;
                                $itemImg = $storeProd?->primaryImage ?: $storeProd?->images->first();
                            @endphp
                            <div class="store-summary-item d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    @if($itemImg)
                                        <img src="{{ asset($itemImg->image_path) }}" alt="{{ $item['product']->name }}" style="width: 44px; height: 44px; object-fit: cover; border-radius: 6px;">
                                    @endif
                                    <div>
                                        <strong class="d-block" style="font-size: 14px; color: var(--primary-color);">
                                            {{ $storeProd->store_title ?? $item['product']->name }}
                                        </strong>
                                        <span class="text-muted" style="font-size: 12px;">Qty: {{ $item['quantity'] }} × ₦{{ number_format($item['unit_price'], 2) }}</span>
                                    </div>
                                </div>
                                <strong style="font-size: 14px; color: var(--accent-color);">₦{{ number_format($item['subtotal'], 2) }}</strong>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>Items subtotal</span>
                        <strong>₦{{ number_format($subtotal, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>Flat shipping</span>
                        <strong>{{ $shippingFee > 0 ? '₦' . number_format($shippingFee, 2) : 'Free' }}</strong>
                    </div>
                    <div class="store-summary-total d-flex justify-content-between align-items-center py-3 my-2 border-bottom">
                        <span style="font-size: 16px; font-weight: 600;">Total</span>
                        <strong style="font-size: 20px; color: var(--accent-color);">₦{{ number_format($total, 2) }}</strong>
                    </div>

                    <p class="store-summary-note text-muted mb-0 mt-3" style="font-size: 12px; line-height: 1.5;">
                        <i class="fa-solid fa-shield-halved text-success me-1"></i> You will be securely redirected to Paystack to complete your card or bank transfer payment.
                    </p>
                </aside>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var pickerButtons = document.querySelectorAll('.address-picker-btn');
    var addressInput = document.getElementById('delivery_address');
    var phoneInput = document.getElementById('delivery_phone');

    pickerButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var addr = this.getAttribute('data-address');
            var ph = this.getAttribute('data-phone');
            if (addr) addressInput.value = addr;
            if (ph && ph !== '') phoneInput.value = ph;

            pickerButtons.forEach(function(b) {
                b.classList.remove('btn-dark');
                b.classList.add('btn-outline-secondary');
            });
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-dark');
        });
    });
});
</script>
@endsection

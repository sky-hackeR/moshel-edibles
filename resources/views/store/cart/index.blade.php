@extends('store.layouts.app')

@section('title', 'Your Shopping Basket')

@section('content')
<div class="page-header bg-section">
    <div class="container">
        <div class="page-header-box">
            <h1>Your Basket</h1>
            {{-- <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('store.welcome') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('store.products') }}">Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cart</li>
                </ol>
            </nav> --}}
        </div>
    </div>
</div>

<section class="store-commerce-section bg-section py-5">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center p-3 rounded-3 mb-4" role="alert" style="background: #e8f5e9; border: 1px solid #c8e6c9;">
                <i class="fa-solid fa-circle-check text-success fs-5 me-3"></i>
                <span class="text-success fw-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center p-3 rounded-3 mb-4" role="alert" style="background: #ffebee; border: 1px solid #ffcdd2;">
                <i class="fa-solid fa-circle-exclamation text-danger fs-5 me-3"></i>
                <span class="text-danger fw-bold">{{ session('error') }}</span>
            </div>
        @endif

        @if($items->isEmpty())
            <div class="store-empty-panel">
                <i class="fa-solid fa-basket-shopping"></i>
                <h2>Your basket is empty</h2>
                <p>You haven't selected any treats yet. Explore our freshly baked breads, cakes, and pastries.</p>
                <a href="{{ route('store.products') }}" class="btn-default btn-highlighted py-3 px-4">
                    <i class="fa-solid fa-bag-shopping me-1"></i> Browse Fresh Bakes
                </a>
            </div>
        @else
            <div class="row g-4 align-items-start">
                <!-- Cart Items List -->
                <div class="col-lg-8">
                    <div class="p-4 bg-white rounded-4 border shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                            <h3 class="mb-0" style="font-size: 22px; font-weight: 700;">
                                <i class="fa-solid fa-basket-shopping me-2 text-muted"></i> Selected Items ({{ $items->sum('quantity') }})
                            </h3>
                            <a href="{{ route('store.products') }}" class="store-text-link">
                                <i class="fa-solid fa-plus me-1"></i> Add More Items
                            </a>
                        </div>

                        <form method="POST" action="{{ route('store.cart.update') }}" id="cartForm">
                            @csrf 
                            @method('PATCH')

                            @foreach($items as $item)
                                @php
                                    $storeProduct = $item['product']->storeProduct;
                                    $itemImg = $storeProduct?->primaryImage ?: $storeProduct?->images->first();
                                    $itemTitle = $storeProduct->store_title ?? $item['product']->name;
                                @endphp
                                <article class="store-line-item">
                                    <div class="store-line-item-image">
                                        <img src="{{ $itemImg ? asset($itemImg->image_path) : asset('frontAssets/images/product-image-1.png') }}" alt="{{ $itemTitle }}">
                                    </div>
                                    
                                    <div class="store-line-item-content">
                                        <h3>
                                            <a href="{{ route('store.productDetails', $item['product']->slug) }}" class="text-dark text-decoration-none">
                                                {{ $itemTitle }}
                                            </a>
                                        </h3>
                                        <p>₦{{ number_format($item['unit_price'], 2) }} per {{ $item['product']->sales_unit }}</p>
                                        
                                        <div class="store-line-item-actions">
                                            <label for="quantity-{{ $item['product']->id }}" class="mb-0">Qty:</label>
                                            <input id="quantity-{{ $item['product']->id }}" type="number" min="1" max="{{ (int) $item['product']->stock_on_hand }}" name="quantities[{{ $item['product']->id }}]" value="{{ $item['quantity'] }}">
                                            
                                            <button type="submit" name="remove_product" value="{{ $item['product']->id }}" class="store-remove-link" title="Remove item">
                                                <i class="fa-regular fa-trash-can me-1"></i> Remove
                                            </button>
                                        </div>
                                    </div>

                                    <strong class="store-line-item-total">₦{{ number_format($item['subtotal'], 2) }}</strong>
                                </article>
                            @endforeach

                            <div class="store-cart-actions">
                                <button class="btn-default py-2 px-4" type="submit">
                                    <i class="fa-solid fa-arrows-rotate me-1"></i> Update Quantities
                                </button>
                                <a href="{{ route('store.products') }}" class="store-text-link">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Continue Shopping
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Summary Sidebar -->
                <div class="col-lg-4">
                    <aside class="store-summary-panel">
                        <span class="store-summary-kicker">Order Summary</span>
                        <h2>Basket Total</h2>

                        <div class="store-summary-row pb-2">
                            <span>Subtotal ({{ $items->sum('quantity') }} items)</span>
                            <strong>₦{{ number_format($total, 2) }}</strong>
                        </div>

                        <div class="store-summary-row pb-2">
                            <span>Lagos Delivery</span>
                            <span class="text-muted" style="font-size: 13px;">Calculated on dispatch</span>
                        </div>

                        <div class="store-summary-total">
                            <span>Total Payable</span>
                            <strong>₦{{ number_format($total, 2) }}</strong>
                        </div>

                        <a href="{{ route('store.checkout') }}" class="btn-default btn-highlighted w-100 text-center py-3 d-inline-flex justify-content-center align-items-center gap-2" style="font-size: 15px; font-weight: 600;">
                            <span>Proceed to Checkout</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>

                        <p class="store-summary-note">
                            <i class="fa-solid fa-shield-halved text-success"></i> 
                            Fast & secure checkout powered by Paystack.
                        </p>
                    </aside>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

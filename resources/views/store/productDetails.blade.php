@extends('store.layouts.app')

@php
    $images = $storeProduct->images;
    $primaryImage = $images->firstWhere('is_primary', true) ?? $images->first();
    $mainImagePath = $primaryImage ? asset($primaryImage->image_path) : asset('frontAssets/images/product-image-1.png');
    $productTitle = $storeProduct->store_title ?: $storeProduct->product->name;
    $ingredients = $storeProduct->product->recipe?->items ?? collect();
@endphp

@section('title', $productTitle)

@section('content')

<!-- Page Header Start -->
<div class="page-header bg-section parallaxie">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="page-header-box">
                    <h1 class="text-anime-style-2" data-cursor="-opaque">
                        {{ $productTitle }}
                    </h1>
                    <nav class="wow fadeInUp">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('store.welcome') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('store.products') }}">Products</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $productTitle }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- Product Single Page Start -->
<div class="page-product-single bg-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- Product Single Box -->
                <div class="page-product-single-box">
                    <div class="product-about-box">
                        <!-- Product Image Gallery Start -->
                        <div class="team-member-image product-card wow fadeInUp">
                            <!-- Main Big Image Container -->
                            <div class="product-main-card mb-3 rounded-4 overflow-hidden border shadow-sm" style="background: #fff;">
                                <a id="mainImageTrigger" href="{{ $mainImagePath }}" title="{{ $productTitle }}" style="display: block; width: 100%; height: 380px; overflow: hidden;">
                                    <img id="mainDisplayImg" src="{{ $mainImagePath }}" alt="{{ $productTitle }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </a>
                            </div>

                            <!-- Gallery Thumbnails -->
                            @if($images->count() > 1)
                                <div class="product-thumbnails-grid d-flex flex-wrap gap-2" id="galleryThumbnails">
                                    @foreach($images as $index => $img)
                                        @php $imgPath = asset($img->image_path); @endphp
                                        <div class="thumbnail-item rounded-3 overflow-hidden border {{ $imgPath === $mainImagePath ? 'active' : '' }}" 
                                            data-src="{{ $imgPath }}" 
                                            data-index="{{ $index }}"
                                            style="width: 70px; height: 70px; cursor: pointer;">
                                            <img src="{{ $imgPath }}" alt="{{ $productTitle }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Hidden Gallery Links for Magnific Popup Loop -->
                            <div class="d-none" id="hiddenMagnificGallery">
                                @foreach($images as $img)
                                    <a href="{{ asset($img->image_path) }}" title="{{ $productTitle }}"></a>
                                @endforeach
                            </div>
                        </div>
                        <!-- Product Image Gallery End -->

                        <!-- Product Single Details Content Start -->
                        <div class="product-single-content ps-lg-4">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <h3 class="wow fadeInUp mb-0" data-wow-delay="0.2s" style="font-size: 30px; font-weight: 700; color: var(--accent-color);">
                                    ₦{{ number_format($storeProduct->product->selling_price, 2) }}
                                </h3>
                                <span class="text-muted" style="font-size: 15px; font-weight: 500;">/ {{ $storeProduct->product->sales_unit }}</span>
                                @if($storeProduct->product->stock_on_hand > 0)
                                    <span class="badge bg-success py-1 px-3 rounded-pill" style="font-size: 12px;">Available Fresh</span>
                                @else
                                    <span class="badge bg-secondary py-1 px-3 rounded-pill" style="font-size: 12px;">Out of Stock</span>
                                @endif
                            </div>

                            <h2 class="text-anime-style-2 mb-3" data-cursor="-opaque" style="font-size: 32px;">{{ $productTitle }}</h2>

                            @if(session('success'))
                                <div class="alert alert-success d-flex align-items-center py-2 px-3 rounded-3 mb-3" style="font-size: 13px; background: #e8f5e9; border: 1px solid #c8e6c9;">
                                    <i class="fa-solid fa-circle-check text-success me-2 fs-5"></i>
                                    <span class="text-success fw-bold">{{ session('success') }}</span>
                                </div>
                            @endif

                            <div class="product-description-excerpt mb-4" style="font-size: 15px; line-height: 1.7; color: var(--text-color);">
                                {!! $storeProduct->short_description ?: '<p>' . \Illuminate\Support\Str::limit(strip_tags($storeProduct->description), 140) . '</p>' !!}
                            </div>

                            <!-- Product Cart Button Start -->
                            @if($storeProduct->product->stock_on_hand > 0)
                                <form class="product-cart-btn wow fadeInUp d-flex align-items-center gap-3 flex-wrap" data-wow-delay="0.4s" method="POST" action="{{ route('store.cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $storeProduct->product->id }}">
                                    
                                    <div class="d-flex align-items-center border rounded-3 px-3 bg-white" style="height: 52px; border-color: #ddd;">
                                        <label for="p-qty" class="me-2 text-muted mb-0" style="font-size: 13px; font-weight: 600;">Qty:</label>
                                        <input id="p-qty" type="number" name="quantity" value="1" min="1" max="{{ (int) $storeProduct->product->stock_on_hand }}" required style="width: 50px; border: 0; outline: none; font-weight: 700; text-align: center; font-size: 15px;">
                                    </div>

                                    <button type="submit" class="btn-default btn-highlighted py-3 px-4 d-inline-flex align-items-center gap-2" style="font-size: 15px; font-weight: 600;">
                                        <i class="fa-solid fa-basket-shopping"></i>
                                        <span>Add to Basket</span>
                                    </button>

                                    <a href="{{ route('store.cart') }}" class="btn-default py-3 px-4" style="font-size: 14px;">
                                        <span>View Basket</span>
                                    </a>
                                </form>
                            @else
                                <div class="p-3 bg-white border rounded-3 text-muted" style="font-size: 14px;">
                                    <i class="fa-regular fa-clock me-1 text-warning"></i> This treat is currently baking in new batches. <a href="{{ route('store.contact') }}" class="fw-bold" style="color: var(--accent-color);">Request a special batch here</a>.
                                </div>
                            @endif
                            <!-- Product Cart Button End -->                               
                        </div>
                        <!-- Product Single Details Content End -->
                    </div>

                    <!-- Tabs: Description & Ingredients -->
                    <div class="product-single-info mt-5">
                        <div class="product-single-box tab-content wow fadeInUp" data-wow-delay="0.25s">
                            <!-- Step Nav start -->
                            <div class="product-step-nav">
                                <ul class="nav nav-tabs" id="productTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#descPane" type="button" role="tab" aria-selected="true">
                                            <i class="fa-solid fa-file-lines me-1"></i> Description & Highlights
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="ingr-tab" data-bs-toggle="tab" data-bs-target="#ingrPane" type="button" role="tab" aria-selected="false">
                                            <i class="fa-solid fa-wheat-awn me-1"></i> Ingredients & Recipe Notes
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <!-- Step Nav End -->
                            
                            <!-- Description Tab Pane -->
                            <div class="product-tab-item-box tab-pane fade show active p-4 bg-white rounded-bottom border border-top-0" id="descPane" role="tabpanel">
                                <div class="product-tab-item-content" style="line-height: 1.8; font-size: 15px;">
                                    {!! $storeProduct->description ?: '<p>' . ($storeProduct->short_description ?: 'Artisanal baked treat made fresh daily from our Lagos kitchen.') . '</p>' !!}
                                </div>
                            </div>
                            
                            <!-- Ingredients Tab Pane -->
                            <div class="product-tab-item-box tab-pane fade p-4 bg-white rounded-bottom border border-top-0" id="ingrPane" role="tabpanel">
                                <div class="product-review-from-content">
                                    <h4 class="d-block mb-3" style="font-size: 18px; font-weight: 700;">Full Ingredient Breakdown</h4>

                                    @if($ingredients->count())
                                        <div class="w-100 d-flex flex-wrap gap-2 align-items-center mb-3">
                                            @foreach($ingredients as $item)
                                                @if($item->ingredient)
                                                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fs-6 fw-normal d-inline-flex align-items-center">
                                                        <i class="fa-solid fa-wheat-awn text-secondary me-2"></i>
                                                        {{ $item->ingredient->name }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">Crafted with pure wholesome baking essentials: unbleached flour, real butter, natural leavening, and premium fillings.</p>
                                    @endif
                                </div>                                    
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <div class="row mt-5 pt-4">
                <div class="col-lg-12">
                    <div class="section-title mb-4">
                        <h2 class="text-anime-style-3">You May Also Savor</h2>
                    </div>

                    <div class="row g-4">
                        @foreach($relatedProducts as $related)
                            @php
                                $relatedImage = $related->primaryImage 
                                    ? asset($related->primaryImage->image_path) 
                                    : asset('frontAssets/images/product-image-1.png');
                                $relatedTitle = $related->store_title ?: $related->product->name;
                            @endphp

                            <div class="col-lg-3 col-md-6">
                                <div class="product-item p-3 bg-white rounded-4 border shadow-sm h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="product-image rounded-3 overflow-hidden mb-3" style="height: 180px;">
                                            <a href="{{ route('store.productDetails', $related->product->slug) }}" class="d-block h-100">
                                                <img src="{{ $relatedImage }}" alt="{{ $relatedTitle }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            </a>
                                        </div>
                                        <div class="product-item-content">
                                            <h3 style="font-size: 17px; font-weight: 600;">
                                                <a href="{{ route('store.productDetails', $related->product->slug) }}" class="text-dark text-decoration-none">
                                                    {{ $relatedTitle }}
                                                </a>
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-2">
                                        <strong style="color: var(--accent-color); font-size: 15px;">₦{{ number_format($related->product->selling_price, 2) }}</strong>
                                        <a href="{{ route('store.productDetails', $related->product->slug) }}" class="btn-default btn-highlighted py-1 px-3" style="font-size: 11px;">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- Product Single Page End -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mainImg = document.getElementById('mainDisplayImg');
        const mainTrigger = document.getElementById('mainImageTrigger');
        const thumbnails = document.querySelectorAll('.thumbnail-item');
        let currentIndex = 0;

        thumbnails.forEach((thumb) => {
            thumb.addEventListener('click', function () {
                const newSrc = this.getAttribute('data-src');
                currentIndex = parseInt(this.getAttribute('data-index'));

                if (mainImg) mainImg.src = newSrc;
                if (mainTrigger) mainTrigger.href = newSrc;

                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });

        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.magnificPopup !== 'undefined') {
            $('#mainImageTrigger').on('click', function (e) {
                e.preventDefault();
                $('#hiddenMagnificGallery').magnificPopup({
                    delegate: 'a',
                    type: 'image',
                    gallery: { enabled: true }
                }).magnificPopup('open', currentIndex);
            });
        }
    });
</script>
@endsection

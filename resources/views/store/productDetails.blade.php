@extends('store.layouts.app')
@php
    $images = $storeProduct->images;
    $primaryImage = $images->firstWhere('is_primary', true) ?? $images->first();
    $mainImagePath = $primaryImage ? asset($primaryImage->image_path) : asset('frontAssets/images/product-image-1.png');
    $productTitle = $storeProduct->store_title ?: $storeProduct->product->name;
$ingredients = $storeProduct->product->recipe?->items ?? collect();

@endphp

@section('title', 'Our Products')

@section('content')

<div class="page-header bg-section parallaxie">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="page-header-box">
                    <h1 class="text-anime-style-2" data-cursor="-opaque">
                        {{ $storeProduct ? $storeProduct->store_title : 'Product Details' }}
                    </h1>
                    <nav class="wow fadeInUp">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ url('/') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ url('/products') }}">Products</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $storeProduct ? $storeProduct->store_title : 'Product Details' }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Single Page Start -->
<div class="page-product-single bg-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- Page Product Single Box Start -->
                <div class="page-product-single-box">
                    <!-- Product About Box Start -->
                    <div class="product-about-box">
                        <!-- Product Image Start -->
                        <div class="team-member-image product-card wow fadeInUp">
                            <!-- Main Big Image Container -->
                            <div class="product-main-card mb-3">
                                <a id="mainImageTrigger" href="{{ $mainImagePath }}" title="{{ $productTitle }}">
                                    <img id="mainDisplayImg" src="{{ $mainImagePath }}" alt="{{ $productTitle }}">
                                </a>
                            </div>

                            <!-- Gallery Thumbnails -->
                            @if($images->count() > 1)
                                <div class="product-thumbnails-grid d-flex flex-wrap gap-2" id="galleryThumbnails">
                                    @foreach($images as $index => $img)
                                        @php $imgPath = asset($img->image_path); @endphp
                                        <div class="thumbnail-item {{ $imgPath === $mainImagePath ? 'active' : '' }}" 
                                            data-src="{{ $imgPath }}" 
                                            data-index="{{ $index }}">
                                            <img src="{{ $imgPath }}" alt="{{ $productTitle }}">
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
                        <!-- Product Image End -->

                        <!-- Product Single Content Start -->
                        <div class="product-single-content">
                            <h3 class="wow fadeInUp" data-wow-delay="0.2s">₦{{ number_format($storeProduct->product->selling_price, 2) }}</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $storeProduct->store_title }}</h2>

                            <p>{!! $storeProduct->short_description !!}</p>

                            <!-- Product Cart Button Start -->
                            <div class="product-cart-btn wow fadeInUp" data-wow-delay="0.6s">
                                <input type="number" value="1">
                                <a href="#" class="btn-default">Add to cart</a>
                            </div>
                            <!-- Product Cart Button End -->                               
                        </div>
                        <!-- Product Single Content End -->
                    </div>
                    <!-- Product About Box End -->

                    <!-- Product Single Info Start -->
                    <div class="product-single-info">
                        <!-- Product Single Box Start -->
                        <div class="product-single-box tab-content wow fadeInUp" data-wow-delay="0.25s" id="missionvision">
                            <!-- Product Step Nav start -->
                            <div class="product-step-nav">
                                <ul class="nav nav-tabs" id="mvTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="first-tab" data-bs-toggle="tab" data-bs-target="#first" type="button" role="tab" aria-selected="true">Description</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="second-tab" data-bs-toggle="tab" data-bs-target="#second" type="button" role="tab" aria-selected="false">Ingredients</button>
                                    </li>
                                </ul>
                            </div>
                            <!-- Product Step Nav End -->
                            
                            <!-- Product Tab Item Box Start -->
                            <div class="product-tab-item-box tab-pane fade show active" id="first" role="tabpanel">
                                <div class="product-tab-item-content">
                                    <p>{!! $storeProduct->description !!}</p>
                                </div>
                            </div>
                            <!-- Product Tab Item End -->
                            
                            <!-- Product Tab Item Box Start -->
                            <div class="product-tab-item-box tab-pane fade" id="second" role="tabpanel">
                                <div class="product-review-from-content">
                                    <h3 class="d-block mb-1">Full Ingredient List</h3>

                                    <!-- Ingredient Badges Grid -->
                                    @if($ingredients->count())
                                        <div class="w-100 d-flex flex-wrap gap-2 align-items-center">
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
                                        <p class="text-muted">No ingredient details listed for this product.</p>
                                    @endif
                                </div>                                    
                            </div>
                            <!-- Product Tab Item Box End -->
                        </div>
                        <!-- Product Single Box End -->
                    </div> 
                    <!-- Product Single Info End -->
                </div>
                <!-- Page Product Single Box End -->
            </div>
        </div>

        <div class="col-lg-12">
            {{-- <!-- Related Products Box Start -->
            <div class="related-products-box">
                <!-- Section-title Start -->
                <div class="section-title">
                    <h2 class="text-anime-style-3">Other products</h2>
                </div>
                <!-- Section-title End -->

                <!-- Related Products List Start -->
                <div class="our-product-box related-products-list">
                    <!-- Product Item Start -->
                    <div class="product-item wow fadeInUp">
                        <div class="product-image">
                            <img src="{{ asset('frontAssets/images/product-image-2.png') }}" alt="">
                        </div>
                        <div class="product-item-body">
                            <div class="product-rating">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <div class="product-item-content">
                                <h2><a href="product-single.html">Cheese Pastry</a></h2>
                                <h3 class="product-price">$15.00 <span>$20.00</span></h3>
                            </div>
                        </div>
                    </div>
                    <!-- Product Item End -->

                    <!-- Product Item Start -->
                    <div class="product-item wow fadeInUp" data-wow-delay="0.2s">
                        <div class="product-image">
                            <img src="{{ asset('frontAssets/images/product-image-3.png') }}" alt="">
                        </div>
                        <div class="product-item-body">
                            <div class="product-rating">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <div class="product-item-content">
                                <h2><a href="product-single.html">Hot Cross Buns</a></h2>
                                <h3 class="product-price">$50.00 <span>$60.00</span></h3>
                            </div>
                        </div>
                    </div>
                    <!-- Product Item End -->

                    <!-- Product Item Start -->
                    <div class="product-item wow fadeInUp" data-wow-delay="0.4s">
                        <div class="product-image">
                            <img src="{{ asset('frontAssets/images/product-image-4.png') }}" alt="">
                        </div>
                        <div class="product-item-body">
                            <div class="product-rating">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <div class="product-item-content">
                                <h2><a href="product-single.html">Linzer Cookie</a></h2>
                                <h3 class="product-price">$35.00 <span>$45.00</span></h3>
                            </div>
                        </div>
                    </div>
                    <!-- Product Item End -->

                    <!-- Product Item Start -->
                    <div class="product-item wow fadeInUp" data-wow-delay="0.6s">
                        <div class="product-image">
                            <img src="{{ asset('frontAssets/images/product-image-5.png') }}" alt="">
                        </div>
                        <div class="product-item-body">
                            <div class="product-rating">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <div class="product-item-content">
                                <h2><a href="product-single.html">Red Velvet Cake</a></h2>
                                <h3 class="product-price">$30.00 <span>$35.00</span></h3>
                            </div>
                        </div>
                    </div>
                    <!-- Product Item End -->
                </div>
                <!-- Related Products List End -->
            </div>
            <!-- Related Products Box End --> --}}

            <!-- Related Products Box Start -->
            @if(isset($relatedProducts) && $relatedProducts->count() > 0)
                <div class="related-products-box">
                    <!-- Section-title Start -->
                    <div class="section-title">
                        <h2 class="text-anime-style-3">Other products</h2>
                    </div>
                    <!-- Section-title End -->

                    <!-- Related Products List Start -->
                    <div class="our-product-box related-products-list">
                        @foreach($relatedProducts as $related)
                            @php
                                $relatedImage = $related->primaryImage 
                                    ? asset($related->primaryImage->image_path) 
                                    : asset('frontAssets/images/product-image-1.png');
                                $relatedTitle = $related->store_title ?: $related->product->name;
                                $animationDelay = ($loop->index * 0.2) . 's';
                            @endphp

                            <!-- Product Item Start -->
                            <div class="product-item wow fadeInUp" data-wow-delay="{{ $animationDelay }}">
                                <div class="product-image">
                                    <a href="{{ url('/products/' . $related->id) }}">
                                        <img src="{{ $relatedImage }}" alt="{{ $relatedTitle }}" style="border: 11px solid transparent; border-radius: 18px;">
                                    </a>
                                </div>
                                <div class="product-item-body">
                                    <div class="product-item-content">
                                        <h2>
                                            <a href="{{ url('/products/' . $related->id) }}">{{ $relatedTitle }}</a>
                                        </h2>
                                        <h3 class="product-price">
                                            ₦{{ number_format($related->product->selling_price, 2) }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <!-- Product Item End -->
                        @endforeach
                    </div>
                    <!-- Related Products List End -->
                </div>
            @endif
            <!-- Related Products Box End -->
        </div>
    </div>
</div>
<!-- Product Single Page End -->



<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mainImg = document.getElementById('mainDisplayImg');
        const mainTrigger = document.getElementById('mainImageTrigger');
        const thumbnails = document.querySelectorAll('.thumbnail-item');
        let currentIndex = 0;

        // 1. Click thumbnail to update main display photo
        thumbnails.forEach((thumb) => {
            thumb.addEventListener('click', function () {
                const newSrc = this.getAttribute('data-src');
                currentIndex = parseInt(this.getAttribute('data-index'));

                // Update main image source and trigger target
                mainImg.src = newSrc;
                mainTrigger.href = newSrc;

                // Update active styling state
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // 2. Initialize Magnific Popup on the main image click
        $('#mainImageTrigger').on('click', function (e) {
            e.preventDefault();

            // Target hidden links so Magnific iterates through all product images
            $('#hiddenMagnificGallery').magnificPopup({
                delegate: 'a',
                type: 'image',
                gallery: {
                    enabled: true
                }
            }).magnificPopup('open', currentIndex);
        });
    });
</script>
@endsection

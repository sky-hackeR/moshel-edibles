@extends('store.layouts.app')
@php
    $images = $storeProduct->images;
    $primaryImage = $images->firstWhere('is_primary', true) ?? $images->first();
    $mainImagePath = $primaryImage ? asset($primaryImage->image_path) : asset('frontAssets/images/product-image-1.png');
    $productTitle = $storeProduct->store_title ?: $storeProduct->product->name;
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
                        {{-- <div class="team-member-image wow fadeInUp">
                            <figure>
                                <img src="{{ asset('frontAssets/images/product-image-1.png') }}" alt="">
                            </figure>
                        </div> --}}
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
                            <div class="customer-rating-box wow fadeInUp">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <span>(Customer Reviews)</span>
                            </div>
                            <h3 class="wow fadeInUp" data-wow-delay="0.2s">$25.0 <span>$35.00</span></h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">Fruit cupcakes</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.4s">Fruit cupcakes infused with real fruit pieces and topped with a swirl of creamy fruit-flavored frosting. Each bite bursts with the natural sweetness of strawberries, blueberries, mango, or seasonal fruits-bringing a refreshing twist to a classic treat.</p>
                            <ul class="wow fadeInUp" data-wow-delay="0.6s">
                                <li>Made with fresh, juicy fruits</li>
                                <li>Topped with fruit-infused buttercream</li>
                                <li>Perfect for parties, events, or a sweet afternoon treat</li>
                            </ul>                               

                            <!-- Product Cart Button Start -->
                            <div class="product-cart-btn wow fadeInUp" data-wow-delay="0.6s">
                                <input type="number" value="1">
                                <a href="contact.html" class="btn-default">Add to cart</a>
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
                                        <button class="nav-link" id="second-tab" data-bs-toggle="tab" data-bs-target="#second" type="button" role="tab" aria-selected="false">Reviews</button>
                                    </li>
                                </ul>
                            </div>
                            <!-- Product Step Nav End -->
                            
                            <!-- Product Tab Item Box Start -->
                            <div class="product-tab-item-box tab-pane fade show active" id="first" role="tabpanel">
                                <div class="product-tab-item-content">
                                    <h2>Bursting with Freshness Our Signature Fruit Cupcakes</h2>
                                    <p>Absolutely delicious! The cupcakes were incredibly soft and moist, and each one had real fruit pieces that made every bite refreshing and flavorful. I tried the strawberry and mango flavors—both were amazing, but the mango really stole the show. Not too sweet, just perfect. They also looked beautiful, perfect for parties. I'll definitely be ordering again!</p>
                                    <ul>
                                        <li>The Best Fruit Cupcakes I've Ever Had!</li>
                                        <li>Experience the Sweetness of Nature in Every Bite</li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Product Tab Item End -->
                            
                            <!-- Product Tab Item Box Start -->
                            <div class="product-tab-item-box tab-pane fade" id="second" role="tabpanel">
                                <div class="product-review-from-content">
                                    <!-- Customer Review List Start -->
                                    <div class="customer-review-list">
                                        <div class="customer-review-item">
                                            <div class="icon-box">
                                                <img src="{{ asset('frontAssets/images/author-1.jpg') }}" alt="">
                                            </div>
                                            <div class="customer-review-item-body">
                                                <div class="customer-review-item-content">
                                                    <p><span>author</span> - July 01, 2025</p>
                                                    <p>Best Cupcakes, no preservatives love it!</p>
                                                </div>
                                                <div class="customer-review-item-rating">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="customer-review-item">
                                            <div class="icon-box">
                                                <img src="{{ asset('frontAssets/images/author-2.jpg') }}" alt="">
                                            </div>
                                            <div class="customer-review-item-body">
                                                <div class="customer-review-item-content">
                                                    <p>author - July 02, 2025</p>
                                                    <p>Pure freshness in every drop — 5/5!</p>
                                                </div>
                                                <div class="customer-review-item-rating">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Customer Review List End -->

                                    <!-- Contact Form Start -->
                                    <div class="review-form">
                                        <div class="review-form-content">
                                            <h3>Add a review</h3>
                                            <p>Your email address will not be published. Required fields are marked Your rating</p>
                                        </div>
                                        <form id="reviewForm" action="#" method="POST" data-toggle="validator">
                                            <div class="row">                                
                                                <div class="form-group col-md-12 mb-4">
                                                    <input type="text" name="review" class="form-control" id="review" placeholder="Your review" required>
                                                    <div class="help-block with-errors"></div>
                                                </div>

                                                <div class="form-group col-md-6 mb-4">
                                                    <input type="text" name="name" class="form-control" id="name" placeholder="Full Name" required>
                                                    <div class="help-block with-errors"></div>
                                                </div>

                                                <div class="form-group col-md-6 mb-4">
                                                    <input type="email" name ="email" class="form-control" id="email" placeholder="Email" required>
                                                    <div class="help-block with-errors"></div>
                                                </div>

                                                <div class="form-group review-form-note">
                                                    <input type="checkbox" id="#" name="#">
                                                    <label class="form-label">Save my name, email, and website in this browser for the next time I comment.</label>
                                                </div>

                                                <div class="col-md-12">
                                                    <button type="submit" class="btn-default">Submit Message</button>
                                                    <div id="msgSubmit" class="h3 hidden"></div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- Contact Form End -->
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
            <!-- Related Products Box Start -->
            <div class="related-products-box">
                <!-- Section-title Start -->
                <div class="section-title">
                    <h2 class="text-anime-style-3">Related products</h2>
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

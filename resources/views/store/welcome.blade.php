@extends('store.layouts.app')

@php
    $siteName = $pageGlobalData->setting->site_name ?? 'Moshel Edibles';
    $leadProduct = $leadProduct ?? $featuredProducts->first() ?? $allProducts->first();
    $leadImage = $leadProduct?->primaryImage ?: $leadProduct?->images->first();
    $leadTitle = $leadProduct?->store_title ?: $leadProduct?->product->name ?? 'Artisanal Bakes';
@endphp

@section('title', 'Artisanal Breads, Celebration Cakes & Fresh Pastries')

@section('content')

<!-- =====================================================
     HERO SECTION (WARM, BESPOKE & INTUITIVE)
===================================================== -->
<section class="store-hero-section bg-section py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <!-- Hero Copy -->
            <div class="col-lg-6">
                <div class="hero-content-wrap pe-lg-3">
                    <span class="store-summary-kicker d-inline-flex align-items-center gap-2 mb-3">
                        <i class="fa-solid fa-wheat-awn"></i>
                        <span>Handcrafted Daily in Lagos, Nigeria</span>
                    </span>

                    <h1 class="hero-title text-anime-style-2 mb-3" data-cursor="-opaque" style="font-size: clamp(34px, 4.2vw, 54px); font-weight: 800; line-height: 1.15; color: var(--primary-color);">
                        Everyday table bakes, gourmet cakes &amp; savory treats crafted with <span style="color: var(--accent-color);">genuine passion</span>
                    </h1>

                    <p class="hero-description text-muted mb-4" style="font-size: 16px; line-height: 1.8;">
                        From crusty golden whole loaves to rich celebration cakes and flaky savory pies — we bake everyday nourishment and celebration showstoppers using pure, unadulterated ingredients.
                    </p>

                    <div class="d-flex align-items-center gap-3 flex-wrap mb-4">
                        <a href="{{ route('store.products') }}" class="btn-default btn-highlighted py-3 px-4 d-inline-flex align-items-center gap-2" style="font-size: 15px; font-weight: 600;">
                            <i class="fa-solid fa-basket-shopping"></i>
                            <span>Shop Fresh Bakes</span>
                        </a>

                        <a href="{{ route('store.contact') }}" class="btn-default py-3 px-4 d-inline-flex align-items-center gap-2" style="font-size: 15px; font-weight: 600;">
                            <i class="fa-solid fa-cake-candles"></i>
                            <span>Custom Inquiries</span>
                        </a>
                    </div>

                    <!-- Trust Pillars -->
                    <div class="d-flex align-items-center gap-4 flex-wrap pt-3 border-top" style="border-color: rgba(139, 94, 60, 0.15) !important;">
                        <div class="d-flex align-items-center gap-2" style="font-size: 13px; font-weight: 600; color: var(--primary-color);">
                            <i class="fa-solid fa-circle-check text-success"></i>
                            <span>Small-Batch Integrity</span>
                        </div>
                        <div class="d-flex align-items-center gap-2" style="font-size: 13px; font-weight: 600; color: var(--primary-color);">
                            <i class="fa-solid fa-circle-check text-success"></i>
                            <span>100% Real Butter &amp; Cocoa</span>
                        </div>
                        <div class="d-flex align-items-center gap-2" style="font-size: 13px; font-weight: 600; color: var(--primary-color);">
                            <i class="fa-solid fa-circle-check text-success"></i>
                            <span>Lagos Doorstep Dispatch</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hero Media & Spotlight -->
            <div class="col-lg-6">
                <div class="hero-media-wrap position-relative">
                    <div class="hero-main-card rounded-4 overflow-hidden shadow-lg border" style="background: #fff;">
                        <div class="position-relative" style="height: 380px; overflow: hidden; background: #fdfbf7;">
                            <img src="{{ $leadImage ? asset($leadImage->image_path) : asset('frontAssets/images/hero-image.jpg') }}" 
                                 alt="{{ $leadTitle }}" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                            
                            @if($leadProduct)
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm" style="font-size: 12px; font-weight: 700;">
                                        <i class="fa-solid fa-star me-1"></i> Today's Highlight
                                    </span>
                                </div>
                            @endif
                        </div>

                        @if($leadProduct)
                            <div class="p-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: #fff;">
                                <div>
                                    <span class="text-muted d-block" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Fresh From The Oven</span>
                                    <h3 class="mb-1" style="font-size: 20px; font-weight: 700;">
                                        <a href="{{ route('store.productDetails', $leadProduct->product->slug) }}" class="text-dark text-decoration-none">
                                            {{ $leadTitle }}
                                        </a>
                                    </h3>
                                    <strong style="font-size: 18px; color: var(--accent-color);">₦{{ number_format($leadProduct->product->selling_price, 2) }}</strong>
                                    <small class="text-muted">/ {{ $leadProduct->product->sales_unit }}</small>
                                </div>

                                <a href="{{ route('store.productDetails', $leadProduct->product->slug) }}" class="btn-default btn-highlighted py-2 px-3" style="font-size: 13px;">
                                    <span>View Details</span> <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- HERO SECTION END -->


<!-- =====================================================
     OUR BAKERY SPECIALTIES
===================================================== -->
<section class="store-specialties-section bg-section py-5">
    <div class="container">
        <div class="row align-items-end mb-4">
            <div class="col-lg-7">
                <div class="section-title mb-0">
                    <span class="store-summary-kicker">What We Bake</span>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">Freshly crafted for everyday tables &amp; <span>milestone celebrations</span></h2>
                </div>
            </div>
            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('store.products') }}" class="store-text-link fw-bold" style="font-size: 14px;">
                    View Complete Bakery Catalog <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="p-4 rounded-4 bg-white border shadow-sm h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white mb-3" style="width: 50px; height: 50px; background: var(--accent-color);">
                            <i class="fa-solid fa-bread-slice fs-5"></i>
                        </div>
                        <h3 style="font-size: 19px; font-weight: 700;" class="mb-2">Artisanal Loaves</h3>
                        <p class="text-muted" style="font-size: 13px; line-height: 1.6;">
                            Slow-fermented breads, whole wheat sandwich loaves, and wholesome morning bakes with natural crumb.
                        </p>
                    </div>
                    <a href="{{ route('store.products') }}" class="store-text-link mt-3 fw-bold" style="font-size: 13px;">
                        Explore Loaves <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="p-4 rounded-4 bg-white border shadow-sm h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white mb-3" style="width: 50px; height: 50px; background: var(--primary-color);">
                            <i class="fa-solid fa-cake-candles fs-5"></i>
                        </div>
                        <h3 style="font-size: 19px; font-weight: 700;" class="mb-2">Gourmet Cakes</h3>
                        <p class="text-muted" style="font-size: 13px; line-height: 1.6;">
                            Moist triple-layer chocolate fudge, velvety red velvet, and custom styled birthday and anniversary tiers.
                        </p>
                    </div>
                    <a href="{{ route('store.products') }}" class="store-text-link mt-3 fw-bold" style="font-size: 13px;">
                        Explore Cakes <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="p-4 rounded-4 bg-white border shadow-sm h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white mb-3" style="width: 50px; height: 50px; background: var(--accent-color);">
                            <i class="fa-solid fa-utensils fs-5"></i>
                        </div>
                        <h3 style="font-size: 19px; font-weight: 700;" class="mb-2">Savory Pastries</h3>
                        <p class="text-muted" style="font-size: 13px; line-height: 1.6;">
                            Generously filled golden meat pies, flaky pastries, and fresh breakfast finger foods.
                        </p>
                    </div>
                    <a href="{{ route('store.products') }}" class="store-text-link mt-3 fw-bold" style="font-size: 13px;">
                        Explore Savories <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="p-4 rounded-4 bg-white border shadow-sm h-100 d-flex flex-column justify-content-between" style="border: 2px dashed var(--accent-color) !important; background: rgba(139, 94, 60, 0.03) !important;">
                    <div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white mb-3" style="width: 50px; height: 50px; background: #222;">
                            <i class="fa-solid fa-wand-magic-sparkles fs-5"></i>
                        </div>
                        <h3 style="font-size: 19px; font-weight: 700;" class="mb-2">Custom Inquiries</h3>
                        <p class="text-muted" style="font-size: 13px; line-height: 1.6;">
                            Have a specific recipe, dietary requirement, celebration theme, or bulk event order?
                        </p>
                    </div>
                    <a href="{{ route('store.contact') }}" class="store-text-link mt-3 fw-bold" style="font-size: 13px;">
                        Send Inquiries <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- SPECIALTIES END -->


<!-- =====================================================
     CURRENT FRESH SHELF (DYNAMIC DB PRODUCTS)
===================================================== -->
<section class="store-products-section bg-section py-5">
    <div class="container">
        <div class="row align-items-end mb-4">
            <div class="col-lg-8">
                <div class="section-title mb-0">
                    <span class="store-summary-kicker">Today's Fresh Shelf</span>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">Baked fresh &amp; ready for <span>your table</span></h2>
                    <p class="text-muted mt-2" style="font-size: 14px;">Handcrafted in small batches with uncompromised quality. Choose your favourites for fast delivery.</p>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('store.products') }}" class="btn-default py-2 px-4" style="font-size: 13px;">
                    <i class="fa-solid fa-bag-shopping me-1"></i> View All Items
                </a>
            </div>
        </div>

        @if($allProducts->isNotEmpty())
            <div class="row g-4">
                @foreach($allProducts as $idx => $sp)
                    @php
                        $prod = $sp->product;
                        $prodImg = $sp->primaryImage ?: $sp->images->first();
                        $title = $sp->store_title ?: $prod->name;
                        $ingredients = $prod->recipe?->items ?? collect();
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <article class="store-product-card p-4 rounded-4 shadow-sm bg-white border h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="product-item-image mb-3 overflow-hidden rounded-3 position-relative" style="height: 230px; background: #fdfbf7;">
                                    <a href="{{ route('store.productDetails', $prod->slug) }}" class="d-block h-100">
                                        <img src="{{ $prodImg ? asset($prodImg->image_path) : asset('frontAssets/images/product-1.jpg') }}" 
                                             alt="{{ $title }}" 
                                             style="width: 100%; height: 100%; object-fit: cover; transition: transform .4s ease;">
                                    </a>
                                    
                                    @if($sp->is_featured)
                                        <div class="position-absolute top-0 start-0 m-2">
                                            <span class="badge bg-warning text-dark px-2 py-1 rounded-pill" style="font-size: 10px; font-weight: 700;">
                                                <i class="fa-solid fa-star me-1"></i> Featured
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="product-item-content">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="text-muted" style="font-size: 12px; font-weight: 700;">0{{ $idx + 1 }}.</span>
                                        @if($prod->stock_on_hand > 0)
                                            <span class="badge bg-success py-1 px-2 rounded-pill" style="font-size: 11px;">Available Fresh</span>
                                        @else
                                            <span class="badge bg-secondary py-1 px-2 rounded-pill" style="font-size: 11px;">Out of Stock</span>
                                        @endif
                                    </div>
                                    
                                    <h3 class="mb-2" style="font-size: 20px; font-weight: 700;">
                                        <a href="{{ route('store.productDetails', $prod->slug) }}" class="text-dark text-decoration-none">
                                            {{ $title }}
                                        </a>
                                    </h3>
                                    
                                    <p class="text-muted mb-3" style="font-size: 13px; line-height: 1.6;">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($sp->short_description ?: $sp->description ?: 'Freshly handcrafted with unadulterated ingredients.'), 95) }}
                                    </p>

                                    @if($ingredients->count())
                                        <div class="d-flex flex-wrap gap-1 mb-3">
                                            @foreach($ingredients->take(3) as $item)
                                                @if($item->ingredient)
                                                    <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 10px; font-weight: 500;">
                                                        {{ $item->ingredient->name }}
                                                    </span>
                                                @endif
                                            @endforeach
                                            @if($ingredients->count() > 3)
                                                <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 10px;">+{{ $ingredients->count() - 3 }} more</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-2">
                                <div>
                                    <strong style="font-size: 18px; color: var(--accent-color);">₦{{ number_format($prod->selling_price, 2) }}</strong>
                                    <small class="text-muted d-block" style="font-size: 11px;">/ {{ $prod->sales_unit }}</small>
                                </div>
                                <a href="{{ route('store.productDetails', $prod->slug) }}" class="btn-default btn-highlighted py-2 px-3" style="font-size: 13px;">
                                    <span>View Treat</span> <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <div class="store-products-empty">
                <div class="store-products-empty-icon">
                    <i class="fa-solid fa-wheat-awn"></i>
                </div>
                <h3>Our online shelf is being freshly updated</h3>
                <p>Our bakers are prepping today's batches. Reach out directly for custom celebration orders.</p>
                <a href="{{ route('store.contact') }}" class="btn-default btn-highlighted mt-2">Custom Order Inquiry</a>
            </div>
        @endif
    </div>
</section>
<!-- FRESH SHELF END -->


<!-- =====================================================
     THE MOSHEL CRAFT & PHILOSOPHY
===================================================== -->
<section class="store-story-section bg-section py-5" style="background: #faf7f2;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="section-title mb-4">
                    <span class="store-summary-kicker">The Moshel Craft</span>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">
                        Unrushed baking, pure ingredients &amp; <span>uncompromising taste</span>
                    </h2>
                    <p class="mt-3 text-muted" style="font-size: 15px; line-height: 1.8;">
                        At {{ $siteName }}, baking is an artisan craft. We reject chemical improvers, pre-mix powders, and artificial shortcuts. Instead, our breads undergo slow natural fermentation to unlock deep flavor and easy digestion.
                    </p>
                    <p class="text-muted" style="font-size: 15px; line-height: 1.8;">
                        Our celebration cakes are built on rich, moist sponge layers infused with pure butter and premium cocoa, frosted with velvety handcrafted buttercream and smooth chocolate ganache.
                    </p>
                </div>

                <div class="d-flex flex-column gap-2 mb-4">
                    <div class="d-flex align-items-center gap-2" style="font-size: 14px; font-weight: 600;">
                        <i class="fa-solid fa-circle-check text-success"></i>
                        <span>Slow 24-Hour Natural Fermentation for Superior Crumb</span>
                    </div>
                    <div class="d-flex align-items-center gap-2" style="font-size: 14px; font-weight: 600;">
                        <i class="fa-solid fa-circle-check text-success"></i>
                        <span>Bespoke Cake Styling for Birthdays, Weddings &amp; Events</span>
                    </div>
                    <div class="d-flex align-items-center gap-2" style="font-size: 14px; font-weight: 600;">
                        <i class="fa-solid fa-circle-check text-success"></i>
                        <span>Zero Artificial Fillers or Synthetic Flavorings</span>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <a href="{{ url('/about') }}" class="btn-default py-3 px-4" style="font-size: 14px;">
                        <span>Our Full Kitchen Story</span>
                    </a>
                    <a href="{{ route('store.contact') }}" class="btn-default btn-highlighted py-3 px-4" style="font-size: 14px;">
                        <span>Special Requests</span>
                    </a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="rounded-4 overflow-hidden shadow-sm" style="height: 260px;">
                            <img src="{{ asset('frontAssets/images/about-us-image-1.jpg') }}" alt="Crafted bakes" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="rounded-4 overflow-hidden shadow-sm mt-4" style="height: 260px;">
                            <img src="{{ asset('frontAssets/images/about-us-image-2.jpg') }}" alt="Artisanal treats" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- STORY END -->


<!-- =====================================================
     HOW IT WORKS (SIMPLE & INTUITIVE)
===================================================== -->
<section class="store-process-section bg-section py-5">
    <div class="container">
        <div class="row section-row mb-5 text-center">
            <div class="col-lg-8 mx-auto">
                <div class="section-title section-title-center">
                    <span class="store-summary-kicker">From Oven to Table</span>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">
                        Fresh bakes made simple — <span>delivered to your door</span>
                    </h2>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="p-4 rounded-4 bg-white border shadow-sm h-100 text-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white mx-auto mb-3" style="width: 60px; height: 60px; background: var(--accent-color); font-size: 22px; font-weight: 700;">
                        01
                    </div>
                    <h3 style="font-size: 19px; font-weight: 700;" class="mb-2">Choose Your Treats</h3>
                    <p class="text-muted mb-0" style="font-size: 14px; line-height: 1.6;">
                        Browse our active menu of artisanal loaves, decadent cakes, and savory pastries.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="p-4 rounded-4 bg-white border shadow-sm h-100 text-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white mx-auto mb-3" style="width: 60px; height: 60px; background: var(--primary-color); font-size: 22px; font-weight: 700;">
                        02
                    </div>
                    <h3 style="font-size: 19px; font-weight: 700;" class="mb-2">Seamless Checkout</h3>
                    <p class="text-muted mb-0" style="font-size: 14px; line-height: 1.6;">
                        Your address is automatically saved to your profile, and payment is processed securely via Paystack.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="p-4 rounded-4 bg-white border shadow-sm h-100 text-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white mx-auto mb-3" style="width: 60px; height: 60px; background: var(--accent-color); font-size: 22px; font-weight: 700;">
                        03
                    </div>
                    <h3 style="font-size: 19px; font-weight: 700;" class="mb-2">Fresh Doorstep Delivery</h3>
                    <p class="text-muted mb-0" style="font-size: 14px; line-height: 1.6;">
                        Receive your fresh bakes in pristine packaging and savor every single bite.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- PROCESS END -->


<!-- =====================================================
     KITCHEN GALLERY
===================================================== -->
<section class="store-gallery-section bg-section py-5">
    <div class="container-fluid">
        <div class="row section-row mb-4 text-center">
            <div class="col-lg-12">
                <div class="section-title section-title-center">
                    <span class="store-summary-kicker">Kitchen Gallery</span>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">
                        A visual taste of our freshly crafted <span>creations</span>
                    </h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="gallery-slider">
                    <div class="swiper">
                        <div class="swiper-wrapper gallery-items">
                            @php
                                $galleryList = [
                                    'gallery-1.jpg', 'gallery-2.jpg', 'gallery-3.jpg', 
                                    'gallery-4.jpg', 'gallery-5.jpg', 'gallery-6.jpg', 
                                    'gallery-7.jpg', 'gallery-8.jpg'
                                ];
                            @endphp
                            @foreach($galleryList as $gImg)
                                <div class="swiper-slide">
                                    <div class="photo-gallery rounded-3 overflow-hidden shadow-sm" style="height: 230px;">
                                        <a href="{{ asset('frontAssets/images/' . $gImg) }}" class="product-gallery-item d-block h-100" title="Moshel Treat">
                                            <img src="{{ asset('frontAssets/images/' . $gImg) }}" alt="Moshel Treat" style="width: 100%; height: 100%; object-fit: cover;">
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="gallery-btn mt-3">
                            <div class="gallery-button-prev"></div>
                            <div class="gallery-button-next"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- GALLERY END -->


<!-- =====================================================
     CUSTOM CELEBRATION CTA BANNER
===================================================== -->
<section class="store-cta-section bg-section py-5">
    <div class="container">
        <div class="row align-items-center p-4 p-md-5 rounded-4 shadow-sm" style="background: var(--primary-color); color: #fff;">
            <div class="col-lg-8">
                <span class="text-uppercase fw-bold" style="color: var(--accent-color); font-size: 12px; letter-spacing: 1px;">Tailored For Your Occasions</span>
                <h2 class="text-white mt-2 mb-3" style="font-size: 32px; font-weight: 700;">
                    Planning a birthday, anniversary, or special event?
                </h2>
                <p class="text-white-50 mb-0" style="font-size: 15px; max-width: 600px; line-height: 1.7;">
                    Whether it's a multi-tiered celebration cake, corporate breakfast snack assortment, or bespoke recipe request, our bakers are ready to formulate it to perfection.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <div class="d-flex flex-column flex-sm-row justify-content-lg-end gap-3">
                    <a href="{{ route('store.contact') }}" class="btn-default btn-highlighted py-3 px-4 d-inline-flex align-items-center justify-content-center gap-2" style="font-size: 15px; font-weight: 600;">
                        <span>Start Custom Order</span>
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- CTA BANNER END -->

@endsection

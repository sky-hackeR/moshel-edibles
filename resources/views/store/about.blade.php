@extends('store.layouts.app')

@section('title', 'About Us - The Moshel Story')

@section('content')
<!-- Page Header Start -->
<div class="page-header bg-section parallaxie">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="page-header-box">
                    <h1 class="text-anime-style-2" data-cursor="-opaque">
                        Our Kitchen <span>Story</span>
                    </h1>
                    <nav class="wow fadeInUp">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('store.welcome') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">About Us</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- About Story Section Start -->
<div class="about-us bg-section project-cover py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="about-us-content">
                    <div class="section-title">
                        <h3 class="wow fadeInUp"><i class="fa-solid fa-wheat-awn me-2"></i> The Moshel Philosophy</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">
                            Baking with heart, unhurried patience, and <span>culinary passion</span>
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">
                            Moshel Edibles was born from a simple belief: that baked goods should nourish the body and delight the spirit. We avoid mass-production shortcuts in favor of slow, traditional fermentation, authentic ingredients, and careful attention to every batch.
                        </p>
                        <p class="wow fadeInUp" data-wow-delay="0.4s">
                            Whether it is a warm, crusty loaf of artisanal whole wheat sourdough for your family table or an intricately decorated triple-layer chocolate fudge cake for a once-in-a-lifetime milestone, every creation is handcrafted right here in Lagos with pristine dedication.
                        </p>
                    </div>

                    <div class="about-us-list wow fadeInUp" data-wow-delay="0.6s">
                        <ul>
                            <li><i class="fa-solid fa-check text-success me-2"></i> 100% All-Natural Ingredients — Pure butter, zero artificial fillers.</li>
                            <li><i class="fa-solid fa-check text-success me-2"></i> Slow 24-Hour Natural Fermentation for Easy Digestion & Rich Flavor.</li>
                            <li><i class="fa-solid fa-check text-success me-2"></i> Baked Fresh Daily & Delivered Promptly Across Lagos.</li>
                        </ul>
                    </div>

                    <div class="about-us-btn wow fadeInUp mt-4" data-wow-delay="0.8s">
                        <a href="{{ route('store.products') }}" class="btn-default btn-highlighted me-3">
                            <i class="fa-solid fa-bag-shopping me-1"></i> Explore Our Menu
                        </a>
                        <a href="{{ route('store.contact') }}" class="btn-default">
                            <i class="fa-solid fa-cake-candles me-1"></i> Custom Orders
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="about-us-images">
                    <div class="about-image">
                        <figure class="image-anime reveal">
                            <img src="{{ asset('frontAssets/images/about-us-image-1.jpg') }}" alt="Artisanal Baking Process">
                        </figure>
                    </div>
                    
                    <div class="about-image">
                        <figure class="image-anime reveal">
                            <img src="{{ asset('frontAssets/images/about-us-image-2.jpg') }}" alt="Freshly Baked Treats">
                        </figure>
                    </div>

                    <div class="year-experience-circle">
                        <img src="{{ asset('frontAssets/images/year-experience-circle.svg') }}" alt="100% Craft">
                        <h2><span class="counter">100</span>%</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About Story Section End -->

<!-- Kitchen Pillars / Values Start -->
<div class="our-features bg-section py-5">
    <div class="container">
        <div class="row section-row mb-5 text-center">
            <div class="col-lg-8 mx-auto">
                <div class="section-title section-title-center">
                    <h3 class="wow fadeInUp">Our Core Standards</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">
                        What makes every Moshel treat <span>uniquely special</span>
                    </h2>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card p-4 rounded-4 shadow-sm bg-white border h-100 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white mb-3" style="width: 52px; height: 52px; background: var(--accent-color);">
                        <i class="fa-solid fa-seedling fs-4"></i>
                    </div>
                    <h3 style="font-size: 20px; font-weight: 700;">Uncompromised Ingredients</h3>
                    <p class="text-muted" style="font-size: 14px; line-height: 1.6;">
                        We use high-grade Dutch cocoa, unbleached flours, pure vanilla beans, and real butter. No artificial additives or synthetic flavourings.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card p-4 rounded-4 shadow-sm bg-white border h-100 wow fadeInUp" data-wow-delay="0.4s">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white mb-3" style="width: 52px; height: 52px; background: var(--primary-color);">
                        <i class="fa-solid fa-fire-burner fs-4"></i>
                    </div>
                    <h3 style="font-size: 20px; font-weight: 700;">Small-Batch Mastery</h3>
                    <p class="text-muted" style="font-size: 14px; line-height: 1.6;">
                        Every morning our ovens come alive. Small batches allow us to control crumb texture, moisture, crust caramelization, and aromatic freshness.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card p-4 rounded-4 shadow-sm bg-white border h-100 wow fadeInUp" data-wow-delay="0.6s">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white mb-3" style="width: 52px; height: 52px; background: var(--accent-color);">
                        <i class="fa-solid fa-heart fs-4"></i>
                    </div>
                    <h3 style="font-size: 20px; font-weight: 700;">Made for Your Table</h3>
                    <p class="text-muted" style="font-size: 14px; line-height: 1.6;">
                        From everyday breakfast cravings to corporate snack platters and weddings, we bring warmth and culinary celebration to your door.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Kitchen Pillars End -->

<!-- Gallery Showcase Start -->
<div class="our-gallery bg-section py-5">
    <div class="container">
        <div class="row section-row mb-4">
            <div class="col-lg-12 text-center">
                <div class="section-title section-title-center">
                    <h3 class="wow fadeInUp">From Our Lagos Kitchen</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">
                        A celebration of crumb, crust, and <span>sweet artistry</span>
                    </h2>
                </div>
            </div>
        </div>

        <div class="row g-3">
            @php
                $galleryPreview = ['gallery-1.jpg', 'gallery-2.jpg', 'gallery-3.jpg', 'gallery-4.jpg', 'gallery-7.jpg', 'gallery-8.jpg'];
            @endphp
            @foreach($galleryPreview as $idx => $gImg)
                <div class="col-md-4 col-sm-6">
                    <div class="photo-gallery rounded-3 overflow-hidden shadow-sm h-100" style="height: 240px !important;">
                        <a href="{{ asset('frontAssets/images/' . $gImg) }}" class="product-gallery-item d-block h-100">
                            <figure class="mb-0 h-100">
                                <img src="{{ asset('frontAssets/images/' . $gImg) }}" alt="Moshel Kitchen" style="width: 100%; height: 100%; object-fit: cover;">
                            </figure>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Gallery Showcase End -->

<!-- CTA Banner Start -->
<div class="our-features bg-section py-5">
    <div class="container">
        <div class="row align-items-center p-4 p-md-5 rounded-4 shadow-sm" style="background: var(--primary-color); color: #fff;">
            <div class="col-lg-8">
                <span class="text-uppercase fw-bold" style="color: var(--accent-color); font-size: 13px; letter-spacing: 1px;">Ready to Taste the Difference?</span>
                <h2 class="text-white mt-2 mb-3" style="font-size: 32px; font-weight: 700;">
                    Experience bakery-fresh goodness today
                </h2>
                <p class="text-white-50 mb-0" style="font-size: 15px; max-width: 600px;">
                    Order your favourite artisan loaves and cakes online or send us your custom formulation specifications.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <a href="{{ route('store.products') }}" class="btn-default btn-highlighted py-3 px-4 me-2">
                    <span>Order Now</span>
                </a>
                <a href="{{ route('store.contact') }}" class="btn-default py-3 px-4">
                    <span>Custom Inquiry</span>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- CTA Banner End -->
@endsection
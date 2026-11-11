@extends('store.layouts.app')

@section('title', 'Our Products')

@section('content')

<div class="page-header bg-section parallaxie">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="page-header-box">
                    <h1 class="text-anime-style-2" data-cursor="-opaque">
                        Our <span>Products</span>
                    </h1>
                    <nav class="wow fadeInUp">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ url('/') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Products</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-product bg-section">
    <div class="container">
        {{-- PRODUCTS --}}
        @if($products->count())
            <div class="row g-4">
                @foreach($products as $storeProduct)
                    @php
                        $product = $storeProduct->product;
                        $title = $storeProduct->store_title ?: $product->name;
                        $primaryImage = $storeProduct->images->firstWhere('is_primary', true) ?? $storeProduct->images->first();
                        $ingredients = $product->recipe?->items ?? collect();
                    @endphp

                    <div class="col-xl-4 col-md-6">
                        <article 
                            class="store-product-card wow fadeInUp" 
                            data-wow-delay="{{ ($loop->index % 3) * 0.15 }}s"
                        >
                            {{-- IMAGE --}}
                            <div class="store-product-image">
                                @if($primaryImage)
                                    {{-- Visible image --}}
                                    <a 
                                        href="{{ asset($primaryImage->image_path) }}" 
                                        class="product-gallery-item" 
                                        data-product-id="{{ $product->id }}" 
                                        title="{{ $title }}"
                                    >
                                        <img 
                                            src="{{ asset($primaryImage->image_path) }}" 
                                            alt="{{ $primaryImage->alt_text ?: $title }}" 
                                            loading="lazy"
                                        >
                                        <div class="store-product-image-overlay">
                                            <span>
                                                <i class="fa-solid fa-expand"></i>
                                            </span>
                                        </div>
                                    </a>

                                    {{-- FEATURED --}}
                                    @if($storeProduct->is_featured)
                                        <div class="store-product-featured">
                                            <i class="fa-solid fa-star"></i>
                                            Featured
                                        </div>
                                    @endif

                                    {{-- IMAGE COUNT --}}
                                    @if($storeProduct->images->count() > 1)
                                        <div class="store-product-image-count">
                                            <i class="fa-solid fa-images"></i>
                                            {{ $storeProduct->images->count() }}
                                        </div>
                                    @endif

                                    {{-- Hidden gallery images --}}
                                    @foreach($storeProduct->images as $image)
                                        @if($image->id !== $primaryImage->id)
                                            <a 
                                                href="{{ asset($image->image_path) }}" 
                                                class="product-gallery-item product-gallery-hidden" 
                                                data-product-id="{{ $product->id }}" 
                                                title="{{ $image->alt_text ?: $title }}"
                                            ></a>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="store-product-no-image">
                                        <i class="fa-solid fa-image"></i>
                                        <span>No image available</span>
                                    </div>
                                @endif
                            </div>

                            {{-- BODY --}}
                            <div class="store-product-body">
                                {{-- TITLE --}}
                                <h3 class="store-product-title">
                                    <a href="{{ url('/products/' . $product->slug) }}">
                                        {{ $title }}
                                    </a>
                                </h3>

                                {{-- DESCRIPTION --}}
                                @if($storeProduct->short_description)
                                    <p class="store-product-description">
                                        {!! $storeProduct->short_description !!}
                                    </p>
                                @elseif($storeProduct->description)
                                    <p class="store-product-description">
                                        {{ \Illuminate\Support\Str::limit($storeProduct->description, 110) }}
                                    </p>
                                @endif

                                {{-- INGREDIENTS --}}
                                @if($ingredients->count())
                                    <div class="store-product-ingredients">
                                        <div class="store-product-ingredients-title">
                                            <i class="fa-solid fa-wheat-awn"></i>
                                            <span>Ingredients</span>
                                        </div>

                                        <div class="store-product-ingredient-list">
                                            @foreach($ingredients->take(5) as $item)
                                                @if($item->ingredient)
                                                    <span>{{ $item->ingredient->name }}</span>
                                                @endif
                                            @endforeach

                                            @if($ingredients->count() > 5)
                                                <span class="ingredient-more">
                                                    +{{ $ingredients->count() - 5 }} more
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- FOOTER --}}
                                <div class="store-product-footer">
                                    <div class="store-product-price">
                                        <small>Price</small>
                                        <strong>₦{{ number_format($product->selling_price, 2) }}</strong>
                                    </div>

                                    <a href="{{ url('/products/' . $product->slug) }}" class="store-product-view">
                                        <span>View</span>
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            {{-- PAGINATION --}}
            @if($products->hasPages())
                <div class="store-products-pagination">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            {{-- EMPTY STATE --}}
            <div class="store-products-empty">
                <div class="store-products-empty-icon">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <h3>No products available</h3>
                <p>We're currently updating our product catalog. Please check back soon.</p>
                <a href="{{ route('store.welcome') }}" class="btn-default">Return Home</a>
            </div>
        @endif
    </div>
</div>
    



@endsection
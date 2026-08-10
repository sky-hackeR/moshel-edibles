@extends('layouts.app')

@section('title', 'Home of Yummy Tastes')

@section('content')
<<<<<<< HEAD
    <!-- Hero Section -->
    <section class="relative min-h-[85vh] flex items-center overflow-hidden py-16 lg:py-24">
        <!-- Ambient Glow Backdrops -->
        <div class="absolute top-1/4 left-10 w-96 h-96 bg-mosh-magenta/15 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-mosh-purple/20 rounded-full blur-[140px] pointer-events-none"></div>

=======
    <!-- Restored Multi-Column Asymmetric Hero Presentation Layer -->
    <section class="relative min-h-[85vh] flex items-center overflow-hidden py-12">
        <!-- Floating Ambient Glow Backdrop Orb -->
        <div class="absolute top-1/3 left-1/4 w-[500px] h-[500px] bg-mosh-purple/20 rounded-full blur-[140px] pointer-events-none"></div>
        
>>>>>>> 45dd3b1890da09846d777aaa1baf29b62388d037
        <div class="container mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left Narrative Column -->
                <div class="lg:col-span-7 space-y-6 text-left">
<<<<<<< HEAD
                    <div class="inline-flex items-center space-x-2 border border-mosh-magenta/30 bg-mosh-magenta/10 px-4 py-1.5 rounded-full">
                        <i class="mdi mdi-cookie-outline text-mosh-magenta"></i>
                        <span class="text-mosh-magenta text-xs font-bold uppercase tracking-wider">
                            Home of yummy tastes...
                        </span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-serif font-bold text-white id-heading-main tracking-tight leading-[1.15]">
                        Freshly baked treats <br class="hidden sm:inline">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-mosh-magenta to-mosh-purple">crafted with love</span>.
                    </h1>

                    <div class="prose prose-invert dark:prose-invert max-w-xl text-gray-400 id-text-body text-base md:text-lg leading-relaxed">
                        @if(!empty($pageGlobalData->setting->description))
                            {!! nl2br($pageGlobalData->setting->description) !!}
                        @else
                            <p>Indulge in Moshel Edibles' artisanal creations. From decadent pastries to signature confections, every bite is crafted fresh daily using premium ingredients.</p>
                        @endif
                    </div>

                    <!-- CTAs -->
                    <div class="pt-2 flex flex-wrap items-center gap-4">
                        <a href="{{ url('/products') }}" class="bg-mosh-magenta hover:bg-opacity-90 text-white font-semibold px-8 py-4 rounded-xl shadow-lg shadow-mosh-magenta/25 text-sm tracking-wide transition flex items-center space-x-2">
                            <span>Order Online</span>
                            <i class="mdi mdi-arrow-right"></i>
                        </a>
                        <a href="#craft" class="border border-gray-500/20 text-gray-300 id-text-body font-semibold px-8 py-4 rounded-xl text-sm tracking-wide transition hover:bg-mosh-magenta/5 hover:border-mosh-magenta/40">
                            Explore Menu
=======
                    <span class="text-mosh-pink text-xs font-bold uppercase tracking-widest border border-mosh-pink/30 bg-mosh-pink/10 px-4 py-1.5 rounded-full inline-block">
                        Artisanal Kitchen Craft
                    </span>
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-serif font-medium text-white id-heading-main tracking-tight leading-[1.1]">
                        Where luxury flavors meet <br class="hidden sm:inline">
                        <span class="italic text-mosh-pink font-normal">kitchen precision</span>.
                    </h1>
                    <p class="text-gray-400 id-text-body text-base md:text-lg max-w-xl leading-relaxed">
                        Moshel Edibles designs exceptional sweets, custom cakes, and artisanal parfaits across Kwara State. Every recipe is perfectly balanced for sensory perfection.
                    </p>
                    <div class="pt-4 flex flex-wrap gap-4">
                        <a href="{{ url('/products') }}" class="bg-mosh-purple hover:bg-mosh-purpleHover text-white font-bold px-8 py-4 rounded shadow-md text-sm uppercase tracking-wider transition">
                            Explore Our Menu
                        </a>
                        <a href="{{ url('/about') }}" class="border border-gray-500/30 dark:border-gray-800 text-gray-700 dark:text-gray-400 id-text-body font-bold px-8 py-4 rounded text-sm uppercase tracking-wider transition hover:bg-gray-500/5">
                            Our Story
>>>>>>> 45dd3b1890da09846d777aaa1baf29b62388d037
                        </a>
                    </div>

                    <!-- Quick Highlights -->
                    <div class="pt-6 grid grid-cols-3 gap-4 border-t border-mosh-magenta/10 text-xs text-gray-400">
                        <div class="flex items-center space-x-2">
                            <i class="mdi mdi-check-circle text-mosh-magenta text-base"></i>
                            <span>Fresh Daily</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="mdi mdi-check-circle text-mosh-magenta text-base"></i>
                            <span>100% Artisanal</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="mdi mdi-check-circle text-mosh-magenta text-base"></i>
                            <span>Fast Delivery</span>
                        </div>
                    </div>
                </div>

<<<<<<< HEAD
                <!-- Right Visual Display Column -->
                <div class="lg:col-span-5 relative mt-8 lg:mt-0">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        <!-- Main Card Frame -->
                        <div class="glass-card relative border rounded-3xl p-4 shadow-2xl theme-transition">
                            <div class="aspect-square rounded-2xl overflow-hidden bg-mosh-purple/10 relative group">
                                <img src="{{ asset('images/hero-banner.jpg') }}" alt="Moshel Edibles Delicacies" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500" onError="this.src='https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=800&q=80'">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                                
                                <div class="absolute bottom-4 left-4 right-4 text-white">
                                    <span class="bg-mosh-magenta text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md mb-1 inline-block">Featured Batch</span>
                                    <h3 class="text-lg font-serif font-bold">Signature Chef's Special</h3>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Badge 1 -->
                        <div class="absolute -top-6 -left-6 glass-card p-3 rounded-2xl border shadow-xl flex items-center space-x-3 hidden sm:flex">
                            <div class="w-10 h-10 rounded-xl bg-mosh-magenta/20 flex items-center justify-center text-mosh-magenta">
                                <i class="mdi mdi-star text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white id-heading-main">Premium Quality</p>
                                <p class="text-[10px] text-gray-400">Handcrafted Treats</p>
                            </div>
                        </div>

                        <!-- Floating Badge 2 -->
                        <div class="absolute -bottom-6 -right-6 glass-card p-3 rounded-2xl border shadow-xl flex items-center space-x-3 hidden sm:flex">
                            <div class="w-10 h-10 rounded-xl bg-mosh-purple/20 flex items-center justify-center text-mosh-purple">
                                <i class="mdi mdi-truck-fast-outline text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white id-heading-main">Kwara Delivery</p>
                                <p class="text-[10px] text-gray-400">Direct to your door</p>
                            </div>
=======
                <!-- Right Overlapping Display Card Column -->
                <div class="lg:col-span-5 relative mt-6 lg:mt-0">
                    <div class="absolute -inset-1 bg-gradient-to-r from-mosh-pink/20 to-transparent rounded-2xl blur opacity-30"></div>
                    <div class="glass-card relative border rounded-2xl p-8 lg:p-10 transform lg:rotate-2 shadow-2xl theme-transition">
                        <span class="font-mono text-[10px] tracking-widest uppercase text-mosh-pink block mb-2">Boutique Specialty Batch</span>
                        <h3 class="text-2xl font-serif font-medium text-white id-heading-main mb-4">The Premium Infusions</h3>
                        <p class="text-xs text-gray-400 id-text-body leading-relaxed mb-6">
                            Each recipe goes through rigorous culinary logging before leaving our counters. Taste profiles remain perfectly tailored for high-end artisan curation.
                        </p>
                        <div class="border-t border-gray-500/10 pt-4 flex justify-between items-center text-xs">
                            <div>
                                <span class="text-gray-500 block">Next Release Batch</span>
                                <span class="font-mono font-bold text-mosh-pink">Friday, 12:00 PM</span>
                            </div>
                            <i class="mdi mdi-star-four-points text-xl text-mosh-pink/60 animate-spin-slow"></i>
>>>>>>> 45dd3b1890da09846d777aaa1baf29b62388d037
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section id="craft" class="py-20 border-t border-mosh-magenta/10">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                <div>
<<<<<<< HEAD
                    <span class="text-mosh-magenta text-xs font-bold uppercase tracking-widest block mb-1">Prepared Fresh Daily</span>
                    <h2 class="text-3xl md:text-4xl font-serif font-bold text-white id-heading-main">Featured Delicacies</h2>
                </div>
                <a href="{{ url('/products') }}" class="inline-flex items-center text-xs text-mosh-magenta hover:text-mosh-magenta/80 uppercase tracking-wider font-bold group">
                    <span>View Full Menu</span>
                    <i class="mdi mdi-arrow-right ml-1 transform group-hover:translate-x-1 transition"></i>
=======
                    <span class="text-mosh-pink text-xs font-bold uppercase tracking-wider block">Prepared Fresh Daily</span>
                    <h2 class="text-2xl md:text-3xl font-serif font-medium text-white id-heading-main mt-1">Featured Delicacies</h2>
                </div>
                <a href="{{ url('/products') }}" class="text-xs text-mosh-pink hover:underline uppercase tracking-wider font-bold">
                    View Entire Collection <i class="mdi mdi-arrow-right ml-0.5"></i>
>>>>>>> 45dd3b1890da09846d777aaa1baf29b62388d037
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($featuredProducts as $product)
                    @php
                        $unit = $product->sales_unit ?? 'piece';
                    @endphp
<<<<<<< HEAD
                    <div class="glass-card rounded-2xl overflow-hidden flex flex-col justify-between theme-transition border hover:border-mosh-magenta/30 transition duration-300">
                        <!-- Product Image Placeholder or Image -->
                        <div class="h-48 bg-mosh-purple/10 relative overflow-hidden">
                            <img src="{{ $product->image_url ?? 'https://images.unsplash.com/photo-1557089706-6fd9031748c4?auto=format&fit=crop&w=600&q=80' }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            
                            <span class="absolute top-3 right-3 text-xs font-mono font-bold text-white id-price-text bg-mosh-dark/80 backdrop-blur-md px-3 py-1 rounded-full border border-mosh-magenta/30">
                                ₦{{ number_format($product->selling_price, 2) }}
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-white id-heading-main mb-2">{{ $product->name }}</h3>
                                <div class="prose prose-invert dark:prose-invert max-w-none text-xs text-gray-400 id-text-body line-clamp-2 mb-4 leading-relaxed">
                                    {!! $product->description ?? 'Baked with precision and packed fresh for your enjoyment.' !!}
                                </div>
                            </div>

                            <div class="pt-4 border-t border-mosh-magenta/10 flex items-center justify-between text-xs">
                                <span class="text-gray-400">
                                    Unit: <span class="text-white font-mono font-semibold">{{ $unit }}</span>
                                </span>
                                <span class="{{ $product->isLowStock() ? 'text-amber-500 font-bold' : 'text-emerald-400 font-bold' }} flex items-center space-x-1">
                                    <i class="mdi mdi-circle-medium"></i>
                                    <span>{{ $product->stock_on_hand }} {{ $product->stock_on_hand == 1 ? $unit : Str::plural($unit) }} available</span>
                                </span>
                            </div>
                        </div>

                        <!-- Card Footer CTA -->
                        <div class="p-4 bg-mosh-magenta/5 border-t border-mosh-magenta/10">
                            <a href="{{ url('/order?product=' . $product->id) }}" class="w-full py-2.5 bg-mosh-magenta hover:bg-opacity-90 text-white rounded-xl font-semibold text-xs transition flex items-center justify-center space-x-2">
                                <i class="mdi mdi-cart-outline"></i>
                                <span>Order This Delicacy</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-16 border border-dashed border-mosh-magenta/20 rounded-2xl glass-card">
                        <i class="mdi mdi-chef-hat text-4xl text-mosh-magenta/40 block mb-2"></i>
                        <p class="text-gray-400 text-sm italic">Our bakers are currently preparing a new batch of menu offerings.</p>
=======
                    <div class="glass-card rounded-xl p-6 flex flex-col justify-between theme-transition">
                        <div>
                            <div class="flex items-center justify-between border-b border-gray-500/10 pb-3 mb-4 gap-2">
                                <h4 class="text-lg font-bold tracking-wide text-mosh-pink">{{ $product->name }}</h4>
                                <span class="text-sm font-mono font-bold text-white id-price-text bg-mosh-pink/10 px-3 py-1 rounded border border-mosh-pink/20 tracking-tight theme-transition">
                                    ₦{{ number_format($product->selling_price, 2) }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4 italic">
                                Packed beautifully per <span class="font-mono text-mosh-pink font-bold">{{ $unit }}</span>
                            </p>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-500/10 flex items-center justify-between text-xs font-semibold text-gray-500">
                            <div class="flex items-center space-x-1.5">
                                <span class="w-2 h-2 rounded-full {{ $product->stock_on_hand <= 0 ? 'bg-red-500' : ($product->isLowStock() ? 'bg-amber-500' : 'bg-emerald-500') }}"></span>
                                <span>
                                    @if($product->stock_on_hand <= 0)
                                        Out of stock
                                    @else
                                        {{ $product->stock_on_hand }} {{ $product->stock_on_hand == 1 ? $unit : Str::plural($unit) }} left
                                    @endif
                                </span>
                            </div>

                            @if($product->stock_on_hand > 0)
                                <a href="{{ url('/order?product=' . $product->id) }}" class="text-mosh-pink hover:text-mosh-pinkHover font-bold uppercase tracking-wider text-[11px] flex items-center transition">
                                    Order <i class="mdi mdi-chevron-right text-base"></i>
                                </a>
                            @else
                                <span class="text-gray-400 text-[11px] uppercase tracking-wider italic">Sold Out</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 border border-dashed border-gray-400/20 rounded-xl glass-card">
                        <i class="mdi mdi-cookie-alert text-3xl text-mosh-pink block mb-2"></i>
                        <p class="text-gray-500 text-sm italic">Our chefs are currently curating new dynamic flavor recipes.</p>
>>>>>>> 45dd3b1890da09846d777aaa1baf29b62388d037
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
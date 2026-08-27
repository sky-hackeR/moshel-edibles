@extends('store.layouts.app')

@section('title', 'Curated Confections & Artisanal Sweets')

@section('content')
    <!-- Restored Multi-Column Asymmetric Hero Presentation Layer -->
    <section class="relative min-h-[85vh] flex items-center overflow-hidden py-12">
        <!-- Floating Ambient Glow Backdrop Orb -->
        <div class="absolute top-1/3 left-1/4 w-[500px] h-[500px] bg-mosh-purple/20 rounded-full blur-[140px] pointer-events-none"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left Narrative Column -->
                <div class="lg:col-span-7 space-y-6 text-left">
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
                        </a>
                    </div>
                </div>

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
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Collection Items Section -->
    <section id="craft" class="py-20 border-t border-gray-500/5">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                <div>
                    <span class="text-mosh-pink text-xs font-bold uppercase tracking-wider block">Prepared Fresh Daily</span>
                    <h2 class="text-2xl md:text-3xl font-serif font-medium text-white id-heading-main mt-1">Featured Delicacies</h2>
                </div>
                <a href="{{ url('/products') }}" class="text-xs text-mosh-pink hover:underline uppercase tracking-wider font-bold">
                    View Entire Collection <i class="mdi mdi-arrow-right ml-0.5"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($featuredProducts as $product)
                    @php
                        $unit = $product->sales_unit ?? 'piece';
                    @endphp
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
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
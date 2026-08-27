@extends('store.layouts.app')

@section('title', 'Our Menu')

@section('content')
<div class="container mx-auto px-6 py-12">
    <!-- Header Section -->
    <div class="text-center max-w-2xl mx-auto mb-16">
        <span class="text-mosh-pink text-xs font-bold uppercase tracking-widest block mb-2">The Collection</span>
        <h1 class="text-3xl md:text-4xl font-serif font-medium text-white id-heading-main tracking-tight mb-4">
            Browse Our Artisanal Batch Menu
        </h1>
        <p class="text-gray-400 id-text-body text-sm">
            Handcrafted confections balanced carefully with local ingredients, culinary precision, and exceptional flavor notes.
        </p>
    </div>

    <!-- Live Grid Framework -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($products as $product)
            @php
                $activeRecipe = $product->recipe;
            @endphp

            <div class="glass-card rounded-xl p-6 flex flex-col justify-between theme-transition">
                <div>
                    <!-- Product Header -->
                    <div class="flex items-start justify-between border-b border-gray-500/10 pb-4 mb-4 gap-4">
                        <div>
                            <h3 class="text-lg font-bold tracking-wide text-mosh-pink mb-1">{{ $product->name }}</h3>
                            <span class="text-[11px] font-mono font-medium tracking-wider text-gray-500 uppercase">
                                Unit: {{ $product->sales_unit ?? 'piece' }}
                            </span>
                        </div>
                        <span class="text-base font-mono font-bold text-white id-price-text bg-mosh-pink/10 px-3 py-1 rounded border border-mosh-pink/20 tracking-tight theme-transition">
                            ₦{{ number_format($product->selling_price, 2) }}
                        </span>
                    </div>

                    <!-- Dynamic Recipe Formulation Notes -->
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-3 italic theme-transition">
                        @if($activeRecipe && $activeRecipe->is_active)
                            "{{ $activeRecipe->note ?? 'Formulated with artisan quality controls.' }}"
                        @else
                            "No active formulation profile summary assigned to this batch yet."
                        @endif
                    </p>

                    <!-- Gourmet Ingredient Composition List -->
                    <div class="mb-6">
                        <span class="text-[10px] uppercase tracking-widest font-bold text-mosh-pink/80 block mb-2">Composition Base:</span>
                        <div class="flex flex-wrap gap-1.5">
                            @if($activeRecipe && $activeRecipe->is_active && $activeRecipe->items->isNotEmpty())
                                @foreach($activeRecipe->items as $item)
                                    @if($item->ingredient)
                                        <span class="text-[11px] font-medium px-2.5 py-1 bg-white/60 dark:bg-black/40 border border-gray-500/10 rounded-md text-gray-800 dark:text-gray-200 theme-transition shadow-sm">
                                            {{ $item->ingredient->name }}
                                        </span>
                                    @endif
                                @endforeach
                            @else
                                <span class="text-xs text-gray-500 italic">Ingredients unlisted</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer Mechanics & Stock Tracker -->
                <div class="pt-4 border-t border-gray-500/10 flex items-center justify-between text-xs">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-2 h-2 rounded-full {{ $product->stock_on_hand <= 0 ? 'bg-red-500' : ($product->isLowStock() ? 'bg-amber-500' : 'bg-emerald-500') }}"></span>
                        <span class="text-gray-500 font-medium">
                            @php
                                $unit = $product->sales_unit ?? 'piece';
                            @endphp
                            
                            @if($product->stock_on_hand <= 0)
                                Out of stock
                            @else
                                {{ $product->stock_on_hand }} 
                                {{ $product->stock_on_hand == 1 ? $unit : Str::plural($unit) }} available
                            @endif
                        </span>
                    </div>

                    @if($product->stock_on_hand > 0)
                        <a href="{{ url('/order?product=' . $product->id) }}" class="bg-mosh-purple hover:bg-mosh-purpleHover text-white font-bold px-4 py-2 rounded text-[11px] uppercase tracking-wider transition shadow-sm">
                            Order Treat
                        </a>
                    @else
                        <button disabled class="bg-gray-600/30 text-gray-400 font-bold px-4 py-2 rounded text-[11px] uppercase tracking-wider cursor-not-allowed">
                            Sold Out
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 border border-dashed border-gray-400/30 rounded-xl glass-card">
                <i class="mdi mdi-cookie-alert text-3xl text-mosh-pink block mb-3"></i>
                <p class="text-gray-500 text-sm italic">Our dynamic kitchen catalog is currently empty. Check back soon for fresh batches.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
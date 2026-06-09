@extends('layouts.app')

@section('title', 'Our Story')

@section('content')
<main class="min-h-screen">
    <!-- Hero / Brand Vision Segment -->
    <section class="max-w-7xl mx-auto px-4 pt-20 pb-16 text-center">
        <span class="text-xs font-mono uppercase tracking-widest text-mosh-gold bg-mosh-gold/5 border border-mosh-gold/10 px-3 py-1 rounded">
            Our Kitchen Philosophy
        </span>
        <h1 class="text-4xl md:text-5xl font-serif font-bold tracking-tight id-heading-main text-white mt-4 mb-6">
            Elevating Local Flavors <br>With Craft & Consistency
        </h1>
        <p class="text-sm md:text-base text-gray-400 max-w-2xl mx-auto leading-relaxed id-text-body">
            Mosh Edibles brings premium culinary standards to the treats we all know and love. From slow-brewed zobo infusions to perfectly glazed doughnuts and sandwich cakes, we ensure premium quality in every bite.
        </p>
    </section>

    <!-- Detailed Identity Section -->
    <section class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            
            <!-- Left Side Narrative -->
            <div class="space-y-6">
                <h2 class="text-2xl font-serif font-bold text-mosh-gold tracking-wide">
                    The Modern Confectionery Standard
                </h2>
                <div class="space-y-4 text-xs text-gray-400 leading-relaxed id-text-body">
                    <p>
                        We started with a simple belief: our rich Nigerian beverage and pastry culture deserves the highest level of execution. We discarded generic, mass-produced chemical concentrates to focus on authentic flavor architectures made in small, strictly managed batches.
                    </p>
                    <p>
                        Operating out of Lagos, Nigeria, we source our roselle leaves (zobo), millet grains (kunu), and baking ingredients from trusted local farmers and premium suppliers. Every recipe balances classic nostalgia with professional culinary control.
                    </p>
                </div>
                
                <!-- Simple Metric Architecture -->
                <div class="grid grid-cols-3 gap-4 pt-4 border-t border-gray-500/10">
                    <div>
                        <div class="text-xl font-bold text-mosh-gold font-serif">100%</div>
                        <div class="text-[10px] uppercase tracking-wider text-gray-500">Natural Infusions</div>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-mosh-gold font-serif">Daily</div>
                        <div class="text-[10px] uppercase tracking-wider text-gray-500">Fresh Batches</div>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-mosh-gold font-serif">Zero</div>
                        <div class="text-[10px] uppercase tracking-wider text-gray-500">Artificial Preservatives</div>
                    </div>
                </div>
            </div>

            <!-- Right Side Interactive Layout Feature -->
            <div class="theme-transition glass-card p-8 rounded-xl border border-gray-500/10 bg-mosh-card/30 space-y-6">
                <div class="flex items-start space-x-4">
                    <div class="p-2 bg-mosh-gold/10 rounded border border-mosh-gold/20 text-mosh-gold">
                        <i class="mdi mdi-cup-water text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-mosh-gold uppercase tracking-wider">Filtered Beverage Craft</h4>
                        <p class="text-xs text-gray-400 mt-1 leading-relaxed id-text-body">
                            Our signature Zobo, rich Kunu, and fruit punches are meticulously filtered and cold-stabilized to maintain refreshing clarity and sharp flavor depth without using artificial sweeteners.
                        </p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="p-2 bg-mosh-gold/10 rounded border border-mosh-gold/20 text-mosh-gold">
                        <i class="mdi mdi-food text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-mosh-gold uppercase tracking-wider">Precision Pastry Proofing</h4>
                        <p class="text-xs text-gray-400 mt-1 leading-relaxed id-text-body">
                            From light, airy doughnuts to dense, decadent sandwich cakes, our flour hydration and proofing times are calibrated perfectly for consistent crumb structures every single morning.
                        </p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="p-2 bg-mosh-gold/10 rounded border border-mosh-gold/20 text-mosh-gold">
                        <i class="mdi mdi-food-variant text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-mosh-gold uppercase tracking-wider">Hygiene & Safety Controls</h4>
                        <p class="text-xs text-gray-400 mt-1 leading-relaxed id-text-body">
                            We follow strict sanitation parameters. Every pasteurization loop, bottling cycle, and pastry tray meets premium health requirements for complete consumer confidence.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Operational Core Principles -->
    <section class="max-w-7xl mx-auto px-4 py-16 border-t border-gray-500/10 mt-12 mb-8">
        <div class="text-center max-w-xl mx-auto mb-12">
            <h3 class="text-xl font-serif font-bold text-mosh-gold">Our Kitchen Core Directives</h3>
            <p class="text-xs text-gray-400 mt-2 id-text-body">The fundamental principles behind our production lines.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 border border-gray-500/10 rounded bg-transparent space-y-2">
                <span class="text-xs font-mono text-mosh-gold">01 / BOTANICALS</span>
                <h5 class="text-sm font-bold uppercase tracking-wider text-white id-logo-text">Premium Real Spices</h5>
                <p class="text-xs text-gray-400 leading-relaxed id-text-body">We brew exclusively using real ginger, cloves, and indigenous fruits, avoiding synthetic syrup colorings.</p>
            </div>

            <div class="p-6 border border-gray-500/10 rounded bg-transparent space-y-2">
                <span class="text-xs font-mono text-mosh-gold">02 / TEXTURE</span>
                <h5 class="text-sm font-bold uppercase tracking-wider text-white id-logo-text">Baking Excellence</h5>
                <p class="text-xs text-gray-400 leading-relaxed id-text-body">Our traditional sandwich cakes and pastries are handled with zero compromise on butter richness and structural moisture.</p>
            </div>

            <div class="p-6 border border-gray-500/10 rounded bg-transparent space-y-2">
                <span class="text-xs font-mono text-mosh-gold">03 / FRESHNESS</span>
                <h5 class="text-sm font-bold uppercase tracking-wider text-white id-logo-text">Strict Batch Windows</h5>
                <p class="text-xs text-gray-400 leading-relaxed id-text-body">Beverages and pastries carry rigorous production timelines to make sure every order arrives at the peak of freshness.</p>
            </div>
        </div>
    </section>
</main>
@endsection
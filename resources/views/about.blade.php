@extends('layouts.app')

@section('title', 'Our Story')

@section('content')
<main class="min-h-screen py-12 lg:py-20">
    <!-- Hero / Brand Vision Segment -->
    <section class="max-w-7xl mx-auto px-6 text-center relative">
        <!-- Ambient Glow Backdrops -->
        <div class="absolute -top-10 left-1/2 -translate-x-1/2 w-96 h-96 bg-mosh-magenta/10 rounded-full blur-[140px] pointer-events-none"></div>

        <span class="inline-flex items-center space-x-2 text-xs font-bold uppercase tracking-widest text-mosh-magenta bg-mosh-magenta/10 border border-mosh-magenta/20 px-4 py-1.5 rounded-full mb-4">
            <i class="mdi mdi-cookie-outline text-mosh-magenta"></i>
            <span>Our Bakery Story</span>
        </span>
        
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-serif font-bold tracking-tight id-heading-main text-white mt-2 mb-6 max-w-4xl mx-auto leading-tight">
            Crafting Unforgettable Moments <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-mosh-magenta to-mosh-purple">With Passion & Precision</span>
        </h1>

        <div class="prose prose-invert dark:prose-invert max-w-3xl mx-auto text-base sm:text-lg text-gray-400 leading-relaxed id-text-body">
            {!! !empty($pageGlobalData->setting->description) ? nl2br($pageGlobalData->setting->description) : 'Moshel Edibles is a premier confectionery brand based in Kwara State, serving clients across Omu-Aran, Ilorin, and surrounding regions. Dedicated to being your ultimate celebration partner.' !!}
        </div>
    </section>

    <!-- Main Story & Regional Footprint -->
    <section class="max-w-7xl mx-auto px-6 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Narrative Column -->
            <div class="lg:col-span-7 space-y-6">
                <span class="text-xs font-bold uppercase tracking-widest text-mosh-magenta block">
                    Your Ultimate Celebration Partner
                </span>
                
                <h2 class="text-3xl font-serif font-bold text-white id-heading-main leading-snug">
                    Specializing in Exquisite Custom Cakes & Confections
                </h2>

                <div class="prose prose-invert dark:prose-invert max-w-none text-sm text-gray-400 leading-relaxed id-text-body">
                    @if(!empty($pageGlobalData->setting->about_us_content))
                        {!! $pageGlobalData->setting->about_us_content !!}
                    @else
                        <p>
                            <strong class="text-white">Moshel Edibles</strong> is a premier confectionery brand based in Kwara State, proudly serving clients across <span class="text-mosh-magenta font-medium">Omu-Aran, Ilorin</span>, and surrounding regions. Dedicated to being your ultimate celebration partner, we specialize in crafting exquisite, custom-designed cakes, decadent desserts, moist banana bread, and signature foil cakes tailored for every milestone.
                        </p>
                        <p>
                            From elegant multi-tiered wedding cakes and custom graduation packages to trendy bento cakes, vintage designs, and savory delights like sandwiches and sweetened yogurt parfait, Moshel Edibles blends rich flavor with stunning visual design. Whether you are celebrating a wedding, birthday, matriculation, or sign-out day, we are committed to turning your special moments into sweet, unforgettable memories.
                        </p>
                        <p class="pt-2">
                            In addition to custom orders, Moshel Edibles offers professional training programs for aspiring bakers, fostering culinary passion and excellence in the art of confectionery.
                        </p>
                    @endif
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-3 gap-4 pt-6 border-t border-mosh-magenta/10">
                    <div>
                        <div class="text-2xl font-bold text-mosh-magenta font-serif">100%</div>
                        <div class="text-[10px] uppercase tracking-wider text-gray-400 mt-1">Fresh Ingredients</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-mosh-magenta font-serif">Custom</div>
                        <div class="text-[10px] uppercase tracking-wider text-gray-400 mt-1">Milestone Designs</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-mosh-magenta font-serif">Kwara</div>
                        <div class="text-[10px] uppercase tracking-wider text-gray-400 mt-1">Delivery Footprint</div>
                    </div>
                </div>
            </div>

            <!-- Visual Feature Card -->
            <div class="lg:col-span-5">
                <div class="theme-transition glass-card p-8 rounded-3xl border space-y-6">
                    <h3 class="text-lg font-serif font-bold text-white id-heading-main border-b border-mosh-magenta/10 pb-4">
                        What We Bake For You
                    </h3>

                    <div class="space-y-5">
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 rounded-xl bg-mosh-magenta/10 border border-mosh-magenta/20 flex items-center justify-center text-mosh-magenta flex-shrink-0">
                                <i class="mdi mdi-cake-variant text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white id-heading-main">Custom & Multi-Tier Cakes</h4>
                                <p class="text-xs text-gray-400 mt-0.5 leading-relaxed id-text-body">
                                    Wedding cakes, graduation packages, trendy bento cakes, and vintage milestone designs.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 rounded-xl bg-mosh-purple/20 border border-mosh-purple/30 flex items-center justify-center text-mosh-magenta flex-shrink-0">
                                <i class="mdi mdi-food-croissant text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white id-heading-main">Desserts & Savory Delights</h4>
                                <p class="text-xs text-gray-400 mt-0.5 leading-relaxed id-text-body">
                                    Moist banana bread, signature foil cakes, sandwiches, and sweetened yogurt parfaits.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 rounded-xl bg-mosh-magenta/10 border border-mosh-magenta/20 flex items-center justify-center text-mosh-magenta flex-shrink-0">
                                <i class="mdi mdi-school-outline text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white id-heading-main">Baking Masterclasses</h4>
                                <p class="text-xs text-gray-400 mt-0.5 leading-relaxed id-text-body">
                                    Professional training programs designed for aspiring bakers to build culinary excellence.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-mosh-magenta/10">
                        <a href="{{ url('/order') }}" class="w-full text-center block bg-mosh-magenta hover:bg-opacity-90 text-white font-semibold py-3 rounded-xl text-xs transition shadow-lg shadow-mosh-magenta/20">
                            Book A Custom Order
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Core Service Pillars -->
    <section class="max-w-7xl mx-auto px-6 py-12 border-t border-mosh-magenta/10 mt-8">
        <div class="text-center max-w-xl mx-auto mb-12">
            <h3 class="text-2xl font-serif font-bold text-white id-heading-main">Our Confectionery Pillars</h3>
            <p class="text-xs text-gray-400 mt-2 id-text-body">The core standards behind every order we craft.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="glass-card p-6 rounded-2xl border space-y-3 theme-transition">
                <span class="text-xs font-mono font-bold text-mosh-magenta">01 / TAILORED DESIGNS</span>
                <h4 class="text-base font-bold text-white id-heading-main">Crafted for Your Story</h4>
                <p class="text-xs text-gray-400 leading-relaxed id-text-body">
                    Every cake design is tailored to reflect the unique spirit of your milestone event, from sign-out days to grand weddings.
                </p>
            </div>

            <div class="glass-card p-6 rounded-2xl border space-y-3 theme-transition">
                <span class="text-xs font-mono font-bold text-mosh-magenta">02 / UNCOMPROMISED TASTE</span>
                <h4 class="text-base font-bold text-white id-heading-main">Rich Flavor Profiles</h4>
                <p class="text-xs text-gray-400 leading-relaxed id-text-body">
                    We use premium baking ingredients to ensure every layer is rich, moist, and unforgettable to taste.
                </p>
            </div>

            <div class="glass-card p-6 rounded-2xl border space-y-3 theme-transition">
                <span class="text-xs font-mono font-bold text-mosh-magenta">03 / ACADEMY & MENTORSHIP</span>
                <h4 class="text-base font-bold text-white id-heading-main">Empowering Future Bakers</h4>
                <p class="text-xs text-gray-400 leading-relaxed id-text-body">
                    Through structured hands-on training programs, we equip future bakers with master techniques and industry standards.
                </p>
            </div>
        </div>
    </section>
</main>
@endsection
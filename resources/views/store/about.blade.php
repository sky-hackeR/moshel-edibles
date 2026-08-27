@extends('store.layouts.app')

@section('title', 'Our Story')

@section('content')
<main class="min-h-screen">
    <!-- Hero / Brand Vision Segment -->
    <section class="max-w-7xl mx-auto px-4 pt-20 pb-16 text-center">
        <span class="text-xs font-mono uppercase tracking-widest text-mosh-pink bg-mosh-pink/10 border border-mosh-pink/20 px-3 py-1 rounded">
            Our Kitchen Philosophy
        </span>
        <h1 class="text-4xl md:text-5xl font-serif font-bold tracking-tight id-heading-main text-white mt-4 mb-6">
            Crafting Unforgettable Moments <br>With Flavor & Passion
        </h1>
    </section>

    <!-- Detailed Identity Section -->
    <section class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            
            <!-- Left Side Narrative -->
            <div class="space-y-6">
                <h2 class="text-2xl font-serif font-bold text-mosh-pink tracking-wide">
                    The Premier Confectionery Standard
                </h2>
                
                <!-- WYSIWYG Full Content Container -->
                <div class="text-xs text-gray-400 leading-relaxed id-text-body wysiwyg-content space-y-4">
                    {!! !empty($pageGlobalData->setting->description) ? $pageGlobalData->setting->description : '
                        <p>Moshel Edibles is a premier confectionery brand based in Kwara State, proudly serving clients across Omu-Aran, Ilorin, and surrounding regions. Dedicated to being your ultimate celebration partner, we specialize in crafting exquisite, custom-designed cakes, decadent desserts, moist banana bread, and signature foil cakes tailored for every special milestone.</p>
                        <p>From elegant multi-tiered wedding cakes and custom graduation packages to trendy bento cakes, vintage designs, and savory delights like sandwiches and sweetened yogurt parfait, Moshel Edibles blends rich flavor with stunning visual design. Whether you are celebrating a wedding, birthday, matriculation, or sign-out day, Moshel Edibles is committed to turning your special moments into unforgettable memories.</p>
                        <p>In addition to custom orders, Moshel Edibles offers professional training programs for aspiring bakers, fostering culinary passion and excellence within our community.</p>
                    ' !!}
                </div>
                
                <!-- Metric Architecture -->
                <div class="grid grid-cols-3 gap-4 pt-4 border-t border-gray-500/10">
                    <div>
                        <div class="text-xl font-bold text-mosh-pink font-serif">100%</div>
                        <div class="text-[10px] uppercase tracking-wider text-gray-500">Custom Crafted</div>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-mosh-pink font-serif">Daily</div>
                        <div class="text-[10px] uppercase tracking-wider text-gray-500">Fresh Bakes</div>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-mosh-pink font-serif">Kwara</div>
                        <div class="text-[10px] uppercase tracking-wider text-gray-500">Omu-Aran & Ilorin</div>
                    </div>
                </div>
            </div>

            <!-- Right Side Interactive Layout Feature -->
            <div class="theme-transition glass-card p-8 rounded-xl border border-mosh-pink/10 bg-mosh-card/30 space-y-6">
                <div class="flex items-start space-x-4">
                    <div class="p-2 bg-mosh-pink/10 rounded border border-mosh-pink/20 text-mosh-pink">
                        <i class="mdi mdi-cake-layered text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-mosh-pink uppercase tracking-wider">Custom & Tiered Cakes</h4>
                        <p class="text-xs text-gray-400 mt-1 leading-relaxed id-text-body">
                            From intricate multi-tiered wedding showstoppers to trendy bento cakes, vintage piping, and signature foil cakes, every creation is tailored to your taste and celebration.
                        </p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="p-2 bg-mosh-pink/10 rounded border border-mosh-pink/20 text-mosh-pink">
                        <i class="mdi mdi-food-croissant text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-mosh-pink uppercase tracking-wider">Desserts & Savory Delights</h4>
                        <p class="text-xs text-gray-400 mt-1 leading-relaxed id-text-body">
                            Indulge in our moist banana bread, sweetened yogurt parfaits, handcrafted sandwiches, and decadent dessert spreads made fresh for any gathering.
                        </p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="p-2 bg-mosh-pink/10 rounded border border-mosh-pink/20 text-mosh-pink">
                        <i class="mdi mdi-school text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-mosh-pink uppercase tracking-wider">Professional Culinary Academy</h4>
                        <p class="text-xs text-gray-400 mt-1 leading-relaxed id-text-body">
                            Empowering future bakers through structured hands-on training programs, teaching foundational precision, advanced icing methods, and confectionery business skills.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Operational Core Principles -->
    <section class="max-w-7xl mx-auto px-4 py-16 border-t border-gray-500/10 mt-12 mb-8">
        <div class="text-center max-w-xl mx-auto mb-12">
            <h3 class="text-xl font-serif font-bold text-mosh-pink">Our Kitchen Core Directives</h3>
            <p class="text-xs text-gray-400 mt-2 id-text-body">The fundamental principles behind our confectionery production.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 border border-gray-500/10 rounded bg-transparent space-y-2">
                <span class="text-xs font-mono text-mosh-pink">01 / ARTISANAL QUALITY</span>
                <h5 class="text-sm font-bold uppercase tracking-wider text-white id-logo-text">Premium Ingredients</h5>
                <p class="text-xs text-gray-400 leading-relaxed id-text-body">We source the finest baking ingredients, rich dairy, and fresh fruits to ensure every bake is melt-in-your-mouth delicious.</p>
            </div>

            <div class="p-6 border border-gray-500/10 rounded bg-transparent space-y-2">
                <span class="text-xs font-mono text-mosh-pink">02 / CELEBRATION DESIGN</span>
                <h5 class="text-sm font-bold uppercase tracking-wider text-white id-logo-text">Tailored Aesthetics</h5>
                <p class="text-xs text-gray-400 leading-relaxed id-text-body">Every matriculation, wedding, birthday, or sign-out package is customized to reflect your personal story and aesthetic style.</p>
            </div>

            <div class="p-6 border border-gray-500/10 rounded bg-transparent space-y-2">
                <span class="text-xs font-mono text-mosh-pink">03 / ACADEMY EXCELLENCE</span>
                <h5 class="text-sm font-bold uppercase tracking-wider text-white id-logo-text">Empowering Bakers</h5>
                <p class="text-xs text-gray-400 leading-relaxed id-text-body">Our masterclasses foster culinary passion, passing on time-tested techniques to build the next generation of professional bakers.</p>
            </div>
        </div>
    </section>
</main>
@endsection
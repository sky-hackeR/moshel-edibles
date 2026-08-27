@extends('store.layouts.app')

@section('title', 'Custom Orders & Contact')

@section('content')
<main class="min-h-screen">
    <!-- Header Hero Section -->
    <section class="max-w-7xl mx-auto px-4 pt-16 pb-8 text-center">
        <span class="text-xs font-mono uppercase tracking-widest text-mosh-pink bg-mosh-pink/10 border border-mosh-pink/20 px-3 py-1 rounded">
            Special Requests & Custom Inquiries
        </span>
        <h1 class="text-4xl md:text-5xl font-serif font-bold tracking-tight id-heading-main text-white mt-4 mb-4">
            Place an Order or Inquire
        </h1>
        <p class="text-sm text-gray-400 max-w-xl mx-auto leading-relaxed id-text-body">
            Select from our active operational menu line, or use the form details below to describe a new treat concept or event order you would like us to craft for you.
        </p>
    </section>

    <!-- Main Content Container -->
    <section class="max-w-4xl mx-auto px-4 pb-20">
        
        @if(session('success'))
            <div class="mb-6 p-4 text-xs font-semibold rounded bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 text-xs font-semibold rounded bg-rose-500/10 border border-rose-500/20 text-rose-400">
                Please update the highlighted errors below before submitting.
            </div>
        @endif

        <div class="theme-transition glass-card p-6 md:p-10 rounded-xl border border-gray-500/10">
            <form action="{{ url('/contact') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Row 1: Contact Logistics -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-[11px] uppercase tracking-wider font-bold text-mosh-pink block">Your Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full bg-black/20 dark:bg-black/40 border border-gray-500/20 focus:border-mosh-pink focus:outline-none rounded px-4 py-2.5 text-xs text-gray-200 id-text-body transition">
                        @error('name') <span class="text-[11px] text-rose-400 block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] uppercase tracking-wider font-bold text-mosh-pink block">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full bg-black/20 dark:bg-black/40 border border-gray-500/20 focus:border-mosh-pink focus:outline-none rounded px-4 py-2.5 text-xs text-gray-200 id-text-body transition">
                        @error('email') <span class="text-[11px] text-rose-400 block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Row 2: Secondary Identifiers -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-[11px] uppercase tracking-wider font-bold text-mosh-pink block">Phone Number (WhatsApp Preferred) *</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g., +234..." required
                            class="w-full bg-black/20 dark:bg-black/40 border border-gray-500/20 focus:border-mosh-pink focus:outline-none rounded px-4 py-2.5 text-xs text-gray-200 id-text-body transition">
                        @error('phone') <span class="text-[11px] text-rose-400 block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] uppercase tracking-wider font-bold text-mosh-pink block">Required Delivery Date *</label>
                        <input type="date" name="delivery_date" value="{{ old('delivery_date') }}" min="{{ date('Y-m-d') }}" required
                            class="w-full bg-black/20 dark:bg-black/40 border border-gray-500/20 focus:border-mosh-pink focus:outline-none rounded px-4 py-2.5 text-xs text-gray-200 id-text-body transition">
                        @error('delivery_date') <span class="text-[11px] text-rose-400 block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Row 3: Menu Selection Loop -->
                <div class="space-y-2 pt-2">
                    <div class="flex items-center justify-between">
                        <label class="text-[11px] uppercase tracking-wider font-bold text-mosh-pink block">Select Formulations (Optional)</label>
                        <span class="text-[10px] font-mono text-gray-500 italic">Leave empty if requesting something brand new</span>
                    </div>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @php
                            $selectedProductParam = request('product');
                        @endphp

                        @forelse($activeProducts as $product)
                            @php
                                $isChecked = (is_array(old('items')) && in_array($product->name, old('items'))) 
                                    || ($selectedProductParam == $product->id);
                            @endphp
                            <label class="flex items-center space-x-2 bg-black/10 dark:bg-black/30 p-3 rounded border border-gray-500/10 cursor-pointer hover:border-mosh-pink/40 transition">
                                <input type="checkbox" name="items[]" value="{{ $product->name }}" {{ $isChecked ? 'checked' : '' }}
                                    class="rounded border-gray-500/30 text-mosh-purple focus:ring-mosh-purple bg-transparent">
                                <span class="text-xs text-gray-300 id-text-body">{{ $product->name }}</span>
                            </label>
                        @empty
                            <div class="col-span-full text-xs text-gray-500 italic py-2">
                                No specific active catalog items available right now. Feel free to describe your custom order below.
                            </div>
                        @endforelse

                        <!-- Custom Request Checkbox -->
                        <label class="flex items-center space-x-2 bg-mosh-pink/5 p-3 rounded border border-mosh-pink/20 cursor-pointer hover:border-mosh-pink transition">
                            <input type="checkbox" name="items[]" value="Brand New Product Request" {{ is_array(old('items')) && in_array('Brand New Product Request', old('items')) ? 'checked' : '' }}
                                class="rounded border-mosh-pink text-mosh-purple focus:ring-mosh-purple bg-transparent">
                            <span class="text-xs font-semibold text-mosh-pink">Something New! ✨</span>
                        </label>
                    </div>
                </div>

                <!-- Row 4: Specifications -->
                <div class="space-y-1.5">
                    <label class="text-[11px] uppercase tracking-wider font-bold text-mosh-pink block">Order Details, Quantities or Custom Product Specs *</label>
                    <textarea name="specifications" rows="5" required placeholder="If you're asking for a custom treat or cake design, describe flavor notes and tiers here! Otherwise, list desired batch sizes and delivery preferences..."
                        class="w-full bg-black/20 dark:bg-black/40 border border-gray-500/20 focus:border-mosh-pink focus:outline-none rounded px-4 py-2.5 text-xs text-gray-200 id-text-body transition placeholder-gray-600">{{ old('specifications') }}</textarea>
                    @error('specifications') <span class="text-[11px] text-rose-400 block mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Action Block -->
                <div class="pt-4 text-right">
                    <button type="submit" class="bg-mosh-purple hover:bg-mosh-purpleHover text-white font-bold text-xs uppercase tracking-widest px-8 py-3.5 rounded shadow-sm transition duration-200">
                        <i class="mdi mdi-send-outline mr-1.5"></i> Send Request
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>
@endsection
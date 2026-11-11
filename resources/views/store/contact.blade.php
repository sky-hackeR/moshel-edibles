@extends('store.layouts.app')

@section('title', 'Custom Orders & Contact')

@section('content')
<!-- Page Header Start -->
<div class="page-header bg-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="page-header-box">
                    <h1 class="text-anime-style-2" data-cursor="-opaque">Get in Touch with <span>Moshel</span></h1>
                    <nav class="wow fadeInUp">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('store.welcome') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Custom Orders & Contact</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- Contact Us Page Section Start -->
<div class="contact-us-page bg-section py-5">
    <div class="container">
        <!-- Section Header -->
        <div class="row section-row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <div class="section-title section-title-center">
                    <h3 class="wow fadeInUp">Special Requests & Inquiries</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">Let's bake something memorable for <span>your table</span></h2>
                    <p class="wow fadeInUp mt-3" data-wow-delay="0.2s">
                        Have a celebration cake, bespoke pastry batch, corporate catering, or custom recipe in mind? Send us your specs or reach out directly to our kitchen team.
                    </p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="alert alert-success d-flex align-items-center p-4 rounded-3 shadow-sm" role="alert" style="border-left: 5px solid #28a745; background: #e8f5e9;">
                        <i class="fa-solid fa-circle-check fs-3 text-success me-3"></i>
                        <div>
                            <strong class="d-block text-success mb-1" style="font-size: 16px;">Inquiry Received!</strong>
                            <span class="text-dark">{{ session('success') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="alert alert-danger p-4 rounded-3 shadow-sm" role="alert" style="border-left: 5px solid #dc3545; background: #ffebee;">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fa-solid fa-circle-exclamation fs-4 text-danger me-2"></i>
                            <strong class="text-danger">Please correct the highlighted issues:</strong>
                        </div>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li class="text-dark">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-4 align-items-start">
            <!-- Left Column: Contact Cards & Info -->
            <div class="col-lg-4">
                <div class="contact-info-wrapper p-4 rounded-4 shadow-sm bg-white border h-100">
                    <div class="mb-4">
                        <span class="text-uppercase fw-bold" style="color: var(--accent-color); font-size: 12px; letter-spacing: 1px;">Direct Channels</span>
                        <h3 class="mt-1" style="font-size: 22px;">Kitchen Contacts</h3>
                        <p class="text-muted" style="font-size: 14px;">We respond promptly to custom celebration orders, dietary inquiries, and bulk bakes.</p>
                    </div>

                    <!-- Contact Item: Phone -->
                    <div class="d-flex align-items-start gap-3 p-3 mb-3 rounded-3" style="background: rgba(139, 94, 60, 0.05); border: 1px solid rgba(139, 94, 60, 0.1);">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 44px; height: 44px; background: var(--accent-color);">
                            <i class="fa-brands fa-whatsapp fs-5"></i>
                        </div>
                        <div>
                            <span class="d-block text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">WhatsApp / Call</span>
                            <a href="tel:+2348082574927" class="fw-bold text-dark d-block text-decoration-none" style="font-size: 15px;">+234 808 257 4927</a>
                            <small class="text-muted">Direct Kitchen Line</small>
                        </div>
                    </div>

                    <!-- Contact Item: Email -->
                    <div class="d-flex align-items-start gap-3 p-3 mb-3 rounded-3" style="background: rgba(139, 94, 60, 0.05); border: 1px solid rgba(139, 94, 60, 0.1);">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 44px; height: 44px; background: var(--primary-color);">
                            <i class="fa-regular fa-envelope fs-5"></i>
                        </div>
                        <div>
                            <span class="d-block text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Email Inquiries</span>
                            <a href="mailto:hello@moshedibles.com" class="fw-bold text-dark d-block text-decoration-none" style="font-size: 15px;">hello@moshedibles.com</a>
                            <small class="text-muted">Catering & Corporate Orders</small>
                        </div>
                    </div>

                    <!-- Contact Item: Location -->
                    <div class="d-flex align-items-start gap-3 p-3 mb-3 rounded-3" style="background: rgba(139, 94, 60, 0.05); border: 1px solid rgba(139, 94, 60, 0.1);">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 44px; height: 44px; background: var(--accent-color);">
                            <i class="fa-solid fa-location-dot fs-5"></i>
                        </div>
                        <div>
                            <span class="d-block text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Kitchen & Dispatch</span>
                            <strong class="text-dark d-block" style="font-size: 14px;">Lagos, Nigeria</strong>
                            <small class="text-muted">Doorstep Delivery Across Lagos</small>
                        </div>
                    </div>

                    <!-- Working Hours -->
                    <div class="p-3 rounded-3 mt-4" style="background: var(--primary-color); color: #fff;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fa-regular fa-clock" style="color: var(--accent-color);"></i>
                            <strong style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Baking Hours</strong>
                        </div>
                        <ul class="list-unstyled mb-0" style="font-size: 13px; line-height: 1.8;">
                            <li class="d-flex justify-content-between">
                                <span>Mon – Fri:</span>
                                <span class="fw-bold">8:00 AM – 7:00 PM</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Saturday:</span>
                                <span class="fw-bold">9:00 AM – 6:00 PM</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Sunday:</span>
                                <span class="text-warning">Pre-booked Orders Only</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right Column: Custom Order & Inquiry Form -->
            <div class="col-lg-8">
                <div class="contact-form-panel p-4 p-md-5 rounded-4 shadow-sm bg-white border">
                    <div class="mb-4">
                        <span class="text-uppercase fw-bold" style="color: var(--accent-color); font-size: 12px; letter-spacing: 1px;">Tailored Formulation</span>
                        <h3 class="mt-1" style="font-size: 24px;">Send Your Order Specifications</h3>
                        <p class="text-muted" style="font-size: 14px;">Fill in your details below. You can select existing menu formulations or describe a new customized recipe concept.</p>
                    </div>

                    <form action="{{ route('store.contact.submit') }}" method="POST" id="customOrderForm">
                        @csrf

                        <!-- Row 1: Name and Email -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="store-form-field">
                                    <label for="contact-name" class="form-label fw-bold" style="font-size: 13px;">Your Name <span class="text-danger">*</span></label>
                                    <input type="text" id="contact-name" name="name" class="form-control" value="{{ old('name', auth('customer')->user()->name ?? '') }}" placeholder="e.g. Adewale Bakare" required>
                                    @error('name')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="store-form-field">
                                    <label for="contact-email" class="form-label fw-bold" style="font-size: 13px;">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" id="contact-email" name="email" class="form-control" value="{{ old('email', auth('customer')->user()->email ?? '') }}" placeholder="you@example.com" required>
                                    @error('email')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Row 2: Phone and Delivery Date -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="store-form-field">
                                    <label for="contact-phone" class="form-label fw-bold" style="font-size: 13px;">Phone / WhatsApp <span class="text-danger">*</span></label>
                                    <input type="tel" id="contact-phone" name="phone" class="form-control" value="{{ old('phone', auth('customer')->user()->phone ?? '') }}" placeholder="e.g. +234 800 000 0000" required>
                                    @error('phone')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="store-form-field">
                                    <label for="contact-delivery-date" class="form-label fw-bold" style="font-size: 13px;">Required Target Date <span class="text-danger">*</span></label>
                                    <input type="date" id="contact-delivery-date" name="delivery_date" class="form-control" min="{{ date('Y-m-d') }}" value="{{ old('delivery_date') }}" required>
                                    @error('delivery_date')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Menu Item Selections -->
                        <div class="mb-4 pt-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold mb-0" style="font-size: 13px;">Select Formulations / Items (Optional)</label>
                                <span class="text-muted" style="font-size: 11px;">Pick items you want included</span>
                            </div>

                            @php
                                $selectedProductParam = request('product');
                            @endphp

                            <div class="row g-2">
                                @forelse($activeProducts as $product)
                                    @php
                                        $isChecked = (is_array(old('items')) && in_array($product->name, old('items'))) 
                                            || ($selectedProductParam == $product->id);
                                    @endphp
                                    <div class="col-sm-6 col-md-4">
                                        <label class="d-flex align-items-center gap-2 p-2 rounded border cursor-pointer h-100 bg-light-subtle hover-shadow transition" style="cursor: pointer; border-color: #e0e0e0;">
                                            <input type="checkbox" name="items[]" value="{{ $product->name }}" class="form-check-input mt-0" {{ $isChecked ? 'checked' : '' }}>
                                            <span style="font-size: 13px; font-weight: 500;">{{ $product->name }}</span>
                                        </label>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted" style="font-size: 13px;">
                                        <em>Describe your custom treats or recipe below.</em>
                                    </div>
                                @endforelse

                                <div class="col-sm-6 col-md-4">
                                    <label class="d-flex align-items-center gap-2 p-2 rounded border cursor-pointer h-100 bg-light-subtle" style="cursor: pointer; border-color: var(--accent-color);">
                                        <input type="checkbox" name="items[]" value="Brand New Treat Concept" class="form-check-input mt-0" {{ is_array(old('items')) && in_array('Brand New Treat Concept', old('items')) ? 'checked' : '' }}>
                                        <span style="font-size: 13px; font-weight: 600; color: var(--accent-color);">✨ Something New!</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Specifications Textarea -->
                        <div class="mb-4">
                            <label for="contact-specifications" class="form-label fw-bold" style="font-size: 13px;">
                                Order Details, Quantities & Recipe Specifications <span class="text-danger">*</span>
                            </label>
                            <textarea id="contact-specifications" name="specifications" rows="5" class="form-control" required placeholder="Describe what you would like: e.g., 2-tier chocolate fudge cake with vanilla buttercream, red velvet cupcakes for 50 guests, sourdough loaf quantity, special dietary requests (gluten-free, less sugar), delivery address, custom messages on cakes, etc...">{{ old('specifications') }}</textarea>
                            @error('specifications')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn-default btn-highlighted py-3 px-4 d-inline-flex align-items-center gap-2" style="font-size: 15px; font-weight: 600;">
                                <span>Submit Custom Order Inquiry</span>
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact Us Page Section End -->
@endsection
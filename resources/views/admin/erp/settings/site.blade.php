@extends('admin.layout.dashboard')

@section('content')

@php
    $siteName = $setting?->site_name ?? 'Moshel Edibles';
    $siteDescription = $setting?->description ?? 'Artisan bakery, confectionery production, and inventory management system.';
    
    // Resolve logo and favicon safely with filemtime cache busting to prevent stale browser caches
    $cleanLogo = !empty($setting?->logo) ? ltrim($setting->logo, '/\\') : null;
    $hasLogo = $cleanLogo && file_exists(public_path($cleanLogo));
    $logoVersion = $hasLogo ? filemtime(public_path($cleanLogo)) : time();
    $logoUrl = $hasLogo ? asset($cleanLogo) . '?v=' . $logoVersion : asset('assets/images/logo-dark.png');

    $cleanFavicon = !empty($setting?->favicon) ? ltrim($setting->favicon, '/\\') : null;
    $hasFavicon = $cleanFavicon && file_exists(public_path($cleanFavicon));
    $faviconVersion = $hasFavicon ? filemtime(public_path($cleanFavicon)) : time();
    $faviconUrl = $hasFavicon ? asset($cleanFavicon) . '?v=' . $faviconVersion : asset('assets/images/favicon.ico');
@endphp

{{-- PAGE TITLE & TOP ACTION BAR --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-sm-0 font-size-18 text-primary fw-bold">
                    <i class="bx bx-cog me-1 align-middle" style="color: #581a62;"></i> System & Brand Configuration
                </h4>
                <p class="text-muted font-size-12 mb-0">
                    Configure global corporate identity, storefront branding assets, logos, and operational metadata.
                </p>
            </div>
            <div class="page-title-right d-flex align-items-center gap-2">
                <a href="{{ route('store.welcome') }}" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill px-3 shadow-sm">
                    <i class="bx bx-link-external me-1 align-middle"></i> View Live Storefront
                </a>
                <ol class="breadcrumb m-0 ms-2 d-none d-md-flex">
                    <li class="breadcrumb-item active">Site Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- EXECUTIVE BRANDING & HEALTH STATUS CARDS --}}
<div class="row g-3 mb-4">
    {{-- Card 1: Brand Name Status --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Brand Identity"
            value="{{ $siteName }}"
            subtitle="System Core Active"
            subicon="bx bx-check-circle"
            color="purple"
            borderColor="#581a62"
            icon="bx bx-store-alt"
            cardClass="site-kpi-card"
        />
    </div>

    {{-- Card 2: Primary Logo Asset --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Corporate Logo"
            value=""
            color="success"
            borderColor="#10b981"
            icon="bx bx-image-alt"
            cardClass="site-kpi-card"
        >
            <h4 class="mb-0 fw-bold text-dark">
                @if($hasLogo)
                    <span class="text-success font-size-15 fw-semibold"><i class="bx bx-check-double me-1"></i>Active</span>
                @else
                    <span class="text-danger font-size-15 fw-semibold"><i class="bx bx-x-circle me-1"></i>Missing</span>
                @endif
            </h4>
            <small class="text-muted font-size-11 d-block mt-1">Recommended: 200×50px</small>
        </x-kpi-card>
    </div>

    {{-- Card 3: Browser Favicon --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Browser Favicon"
            value=""
            color="info"
            borderColor="#0284c7"
            icon="bx bx-globe"
            cardClass="site-kpi-card"
        >
            <h4 class="mb-0 fw-bold text-dark">
                @if($hasFavicon)
                    <span class="text-info font-size-15 fw-semibold"><i class="bx bx-check-double me-1"></i>Active</span>
                @else
                    <span class="text-warning font-size-15 fw-semibold"><i class="bx bx-alert-circle me-1"></i>Default</span>
                @endif
            </h4>
            <small class="text-muted font-size-11 d-block mt-1">Standard: 32×32px ICO/PNG</small>
        </x-kpi-card>
    </div>

    {{-- Card 4: Runtime Environment --}}
    <div class="col-sm-6 col-xl-3">
        <x-kpi-card
            label="Environment"
            value="{{ ucfirst(config('app.env')) }}"
            subtitle="{{ config('app.timezone', 'Africa/Lagos') }}"
            subicon="bx bx-time-five"
            color="warning"
            borderColor="#f59e0b"
            icon="bx bx-server"
            cardClass="site-kpi-card"
        />
    </div>
</div>

{{-- MAIN CONFIGURATION STUDIO WORKSPACE --}}
<div class="d-flex align-items-start gap-3 w-100 position-relative mb-4" id="studioWorkspaceWrap">

    {{-- SLIM VERTICAL DOCK RAIL (Appears when editor is folded toward sidebar) --}}
    <div id="editorDockRail" class="d-none flex-column align-items-center justify-content-between p-2 bg-white border rounded-3 shadow-sm flex-shrink-0" style="width: 50px; min-width: 50px; height: 750px; transition: all 0.25s ease;">
        <button type="button" class="btn btn-primary btn-sm rounded-circle shadow-sm" id="btnRailUnfold" title="Unfold Editor (Expand form)">
            <i class="bx bx-chevrons-right font-size-18"></i>
        </button>

        <div class="my-auto text-uppercase fw-bold text-muted font-size-11 d-flex align-items-center gap-2" style="writing-mode: vertical-rl; transform: rotate(180deg); letter-spacing: 2px;">
            <i class="bx bx-slider-alt text-primary font-size-14" style="transform: rotate(90deg);"></i>
            <span>Brand Editor</span>
        </div>

        <button type="button" class="btn btn-outline-success btn-sm rounded-circle" id="btnRailSave" title="Save All Changes" onclick="document.getElementById('btnSaveConfig').click();">
            <i class="bx bx-check font-size-16"></i>
        </button>
    </div>

    {{-- MAIN ROW: Editor Form (Left) & Real Live Storefront Emulator (Right) --}}
    <div class="row g-4 flex-grow-1 w-100" id="studioMainRow">

        {{-- LEFT COLUMN: THE INTERACTIVE CONFIGURATION FORM (col-xl-6 by default) --}}
        <div class="col-xl-6 col-lg-6 transition-all" id="editorCol" style="transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bx bx-slider-alt me-1 text-primary"></i> Brand & Corporate Identity Editor
                        </h5>
                        <small class="text-muted">Live edits reflect instantly in the live storefront emulator on the right.</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        {{-- Fold Button --}}
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm d-flex align-items-center gap-1 font-size-12 fw-semibold" id="btnFoldEditor" title="Fold editor toward sidebar to view live storefront in wide screen">
                            <i class="bx bx-arrow-to-left font-size-15"></i>
                            <span class="d-none d-sm-inline">Fold to Sidebar</span>
                        </button>
                        <span class="badge rounded-pill bg-light text-dark border px-3 py-1 font-size-11">
                            ID #{{ $setting?->id ?? 'New' }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ url('/admin/updateSiteInfo') }}" method="POST" enctype="multipart/form-data" id="siteConfigForm">
                        @csrf
                        <input type="hidden" name="site_info_id" value="{{ $setting?->id }}">

                        {{-- SECTION 1: IDENTITY & TEXT DETAILS --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark font-size-13">
                                Site / Brand Name <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="bx bx-building-house font-size-16"></i>
                                </span>
                                <input type="text" 
                                       class="form-control border-start-0 ps-0" 
                                       name="site_name" 
                                       id="inputSiteName" 
                                       value="{{ old('site_name', $siteName) }}" 
                                       placeholder="e.g. Moshel Edibles" 
                                       required 
                                       autocomplete="off">
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <small class="text-muted">Propagates across browser titles, invoices, system headers, and customer storefront.</small>
                                <small class="text-muted" id="siteNameCount">0 / 60</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark font-size-13">
                                Business Tagline & Public Description
                            </label>
                            <textarea name="description" 
                                      id="inputDescription" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="Describe your brand, bakery specialties, and core mission..." 
                                      style="resize: vertical;">{!! old('description', $siteDescription) !!}</textarea>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <small class="text-muted">Used for SEO meta descriptions, storefront about section, and portal footers.</small>
                                <small class="text-muted" id="descriptionCount">0 / 255</small>
                            </div>
                        </div>

                        <hr class="my-4 text-muted opacity-25">

                        {{-- SECTION 2: BRAND ASSETS & UPLOAD DROPZONES --}}
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bx bx-palette me-1 text-primary"></i> Visual Identity & Brand Assets
                            </h6>
                            <small class="text-muted">PNG, SVG, JPG, or ICO supported</small>
                        </div>

                        <div class="row g-3">
                            {{-- Logo Upload Dropzone --}}
                            <div class="col-md-6">
                                <div class="card border border-2 border-dashed h-100 bg-light p-3" 
                                     id="logoDropzone" 
                                     style="border-radius: 10px; cursor: pointer; transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <label class="form-label fw-bold text-dark mb-0 font-size-12">
                                            <i class="bx bx-image-alt me-1 text-primary"></i> Main Logo
                                        </label>
                                        <span class="badge bg-white text-muted border font-size-10">4:1 Ratio</span>
                                    </div>

                                    <div class="text-center py-2">
                                        <div class="mb-2 p-2 bg-white rounded border d-inline-flex align-items-center justify-content-center" 
                                             style="width: 100%; height: 60px; overflow: hidden;" id="logoPreviewContainer">
                                            <img src="{{ $logoUrl }}" 
                                                 id="previewLogoImg" 
                                                 alt="Logo Preview" 
                                                 style="max-height: 48px; max-width: 100%; object-fit: contain;">
                                        </div>
                                        <p class="mb-1 font-size-11 text-muted" id="logoUploadPrompt">
                                            <i class="bx bx-cloud-upload me-1 text-primary font-size-14"></i> Click or drop new logo image
                                        </p>
                                        <span class="badge bg-soft-primary text-primary font-size-10" id="logoFileDetails">
                                            Recommended: 200×50px (Transparent PNG/SVG)
                                        </span>
                                    </div>

                                    <input type="file" 
                                           class="d-none" 
                                           name="logo" 
                                           id="fileInputLogo" 
                                           accept="image/png,image/jpeg,image/svg+xml,image/webp">
                                    
                                    <button type="button" 
                                            class="btn btn-outline-secondary btn-sm w-100 mt-2 font-size-11 rounded-pill" 
                                            onclick="document.getElementById('fileInputLogo').click();">
                                        <i class="bx bx-upload me-1"></i> Select Logo File
                                    </button>
                                </div>
                            </div>

                            {{-- Favicon Upload Dropzone --}}
                            <div class="col-md-6">
                                <div class="card border border-2 border-dashed h-100 bg-light p-3" 
                                     id="faviconDropzone" 
                                     style="border-radius: 10px; cursor: pointer; transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <label class="form-label fw-bold text-dark mb-0 font-size-12">
                                            <i class="bx bx-globe me-1 text-info"></i> Browser Favicon
                                        </label>
                                        <span class="badge bg-white text-muted border font-size-10">1:1 Square</span>
                                    </div>

                                    <div class="text-center py-2">
                                        <div class="mb-2 p-2 bg-white rounded border d-inline-flex align-items-center justify-content-center" 
                                             style="width: 60px; height: 60px; overflow: hidden;" id="faviconPreviewContainer">
                                            <img src="{{ $faviconUrl }}" 
                                                 id="previewFaviconImg" 
                                                 alt="Favicon Preview" 
                                                 style="width: 32px; height: 32px; object-fit: contain;">
                                        </div>
                                        <p class="mb-1 font-size-11 text-muted" id="faviconUploadPrompt">
                                            <i class="bx bx-cloud-upload me-1 text-info font-size-14"></i> Click or drop new favicon
                                        </p>
                                        <span class="badge bg-soft-info text-info font-size-10" id="faviconFileDetails">
                                            Recommended: 32×32px or 64×64px (ICO/PNG)
                                        </span>
                                    </div>

                                    <input type="file" 
                                           class="d-none" 
                                           name="favicon" 
                                           id="fileInputFavicon" 
                                           accept="image/x-icon,image/png,image/jpeg,image/svg+xml">
                                    
                                    <button type="button" 
                                            class="btn btn-outline-secondary btn-sm w-100 mt-2 font-size-11 rounded-pill" 
                                            onclick="document.getElementById('fileInputFavicon').click();">
                                        <i class="bx bx-upload me-1"></i> Select Favicon File
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- SUBMIT BAR --}}
                        <div class="d-flex align-items-center justify-content-between mt-4 pt-3 border-top">
                            <div>
                                <button type="reset" class="btn btn-light btn-sm px-3 rounded-pill" id="btnResetForm">
                                    <i class="bx bx-reset me-1"></i> Reset Changes
                                </button>
                            </div>
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm fw-semibold" id="btnSaveConfig">
                                <i class="bx bx-save me-1 align-middle"></i> Save Configuration
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ASSET AUDIT & STORAGE MATRIX ACCORDION --}}
            @include('admin.erp.partials.site-settings.site-settings-asset-matrix')
        </div>

        {{-- RIGHT COLUMN: REAL LANDING PAGE LIVE EMULATION --}}
        @include('admin.erp.partials.site-settings.site-settings-emulator')
    </div>
</div>

@endsection

@section('scripts')
@include('admin.erp.partials.scripts.site-settings-scripts')
@endsection

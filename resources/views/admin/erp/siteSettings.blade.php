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
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #581a62 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div style="min-width: 0;">
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Brand Identity</p>
                        <h4 class="mb-0 fw-bold text-dark text-truncate" title="{{ $siteName }}">{{ $siteName }}</h4>
                        <small class="text-success font-size-11 fw-medium">
                            <i class="bx bx-check-circle me-1"></i>System Core Active
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0 ms-2">
                        <span class="avatar-title rounded-circle font-size-20" style="background-color: #f7eef8; color: #581a62;">
                            <i class="bx bx-store-alt"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Primary Logo Asset --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #10b981 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Corporate Logo</p>
                        <h4 class="mb-0 fw-bold text-dark">
                            @if($hasLogo)
                                <span class="text-success font-size-15 fw-semibold"><i class="bx bx-check-double me-1"></i>Active</span>
                            @else
                                <span class="text-danger font-size-15 fw-semibold"><i class="bx bx-x-circle me-1"></i>Missing</span>
                            @endif
                        </h4>
                        <small class="text-muted font-size-11">Recommended: 200×50px</small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-success-subtle text-success">
                            <i class="bx bx-image-alt"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: Browser Favicon --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Browser Favicon</p>
                        <h4 class="mb-0 fw-bold text-dark">
                            @if($hasFavicon)
                                <span class="text-info font-size-15 fw-semibold"><i class="bx bx-check-double me-1"></i>Active</span>
                            @else
                                <span class="text-warning font-size-15 fw-semibold"><i class="bx bx-alert-circle me-1"></i>Default</span>
                            @endif
                        </h4>
                        <small class="text-muted font-size-11">Standard: 32×32px ICO/PNG</small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-info-subtle text-info">
                            <i class="bx bx-globe"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Runtime Environment --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase font-size-11 fw-bold mb-1">Environment</p>
                        <h4 class="mb-0 fw-bold text-dark text-capitalize">{{ config('app.env') }}</h4>
                        <small class="text-warning font-size-11 fw-semibold">
                            <i class="bx bx-time-five me-1"></i>{{ config('app.timezone', 'Africa/Lagos') }}
                        </small>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle font-size-20 bg-warning-subtle text-warning">
                            <i class="bx bx-server"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
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
            <div class="card border-0 shadow-sm mt-4" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center justify-content-between cursor-pointer" data-bs-toggle="collapse" data-bs-target="#assetAuditCollapse" style="cursor: pointer;">
                    <h6 class="card-title mb-0 fw-bold text-dark font-size-13">
                        <i class="bx bx-list-check me-1 text-success"></i> Asset Audit & Storage Matrix
                    </h6>
                    <i class="bx bx-chevron-down font-size-16 text-muted"></i>
                </div>
                <div class="collapse show" id="assetAuditCollapse">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-nowrap align-middle mb-0 font-size-12">
                                <thead class="table-light">
                                    <tr>
                                        <th>Component</th>
                                        <th>Storage Path</th>
                                        <th class="text-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">
                                            <i class="bx bx-image-alt text-primary me-1"></i> Main Logo
                                        </td>
                                        <td class="text-muted font-monospace font-size-11">
                                            {{ $setting?->logo ?? 'uploads/siteInfo/logo.png' }}
                                        </td>
                                        <td class="text-end">
                                            @if($hasLogo)
                                                <span class="badge bg-success-subtle text-success">Installed</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Missing</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">
                                            <i class="bx bx-globe text-info me-1"></i> Favicon
                                        </td>
                                        <td class="text-muted font-monospace font-size-11">
                                            {{ $setting?->favicon ?? 'uploads/siteInfo/favicon.png' }}
                                        </td>
                                        <td class="text-end">
                                            @if($hasFavicon)
                                                <span class="badge bg-success-subtle text-success">Installed</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning">Default</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">
                                            <i class="bx bx-envelope text-warning me-1"></i> Email Alerts
                                        </td>
                                        <td class="text-muted font-size-11">
                                            moshedib@moshedibles.com
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-success-subtle text-success">Verified</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">
                                            <i class="bx bx-lock-alt text-secondary me-1"></i> Currency Standard
                                        </td>
                                        <td class="text-muted font-size-11">
                                            Nigerian Naira (NGN ₦)
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-primary-subtle text-primary">Active</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: REAL LANDING PAGE LIVE EMULATION (col-xl-6 by default; expands to col-12 when folded) --}}
        <div class="col-xl-6 col-lg-6 transition-all" id="previewCol" style="transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;" id="storefrontPreviewCard">

                {{-- BROWSER TOP BAR / CHROME --}}
                <div class="card-header bg-dark text-white py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-2">
                        {{-- Window Controls --}}
                        <div class="d-flex align-items-center gap-1 me-1">
                            <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background-color: #ef4444;"></span>
                            <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background-color: #f59e0b;"></span>
                            <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background-color: #10b981;"></span>
                        </div>

                        {{-- Unfold Editor Button (Only appears in top bar when editor is folded) --}}
                        <button type="button" class="btn btn-sm btn-primary rounded-pill py-0 px-2 font-size-11 shadow-sm d-none align-items-center gap-1" id="btnUnfoldEditorTop" title="Bring back the Brand Editor form">
                            <i class="bx bx-arrow-to-right"></i>
                            <span>Unfold Editor</span>
                        </button>

                        <div class="d-flex align-items-center gap-2">
                            <span class="font-size-11 text-light fw-medium">Customer & Browser Experience Preview</span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-size-10 px-2 py-0 d-none d-md-inline-flex align-items-center gap-1" id="liveSyncStatusBadge">
                                <span class="spinner-grow spinner-grow-sm text-success" style="width: 6px; height: 6px;" role="status"></span>
                                Live Sync
                            </span>
                        </div>
                    </div>

                    {{-- DEVICE EMULATION BUTTONS & CONTROLS --}}
                    <div class="d-flex align-items-center gap-2">
                        {{-- Device Switcher --}}
                        <div class="btn-group btn-group-sm bg-black rounded-pill p-1 border border-secondary" role="group">
                            <button type="button" class="btn btn-sm btn-outline-light py-0 px-2 active font-size-11 rounded-pill device-btn" data-device="desktop" title="Desktop View (100% Fluid)">
                                <i class="bx bx-desktop"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-light py-0 px-2 font-size-11 rounded-pill device-btn" data-device="laptop" title="Laptop View (1024px)">
                                <i class="bx bx-laptop"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-light py-0 px-2 font-size-11 rounded-pill device-btn" data-device="tablet" title="Tablet View (768px)">
                                <i class="bx bx-tab"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-light py-0 px-2 font-size-11 rounded-pill device-btn" data-device="mobile" title="Mobile Smartphone View (390px)">
                                <i class="bx bx-mobile-alt"></i>
                            </button>
                        </div>

                        {{-- Zoom / Scale dropdown --}}
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-light py-0 px-2 font-size-10 dropdown-toggle" type="button" data-bs-toggle="dropdown" id="btnZoomLabel">
                                100%
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end font-size-11 shadow">
                                <li><a class="dropdown-item zoom-opt active" href="#" data-zoom="1">100% (Default)</a></li>
                                <li><a class="dropdown-item zoom-opt" href="#" data-zoom="0.9">90% Zoom</a></li>
                                <li><a class="dropdown-item zoom-opt" href="#" data-zoom="0.75">75% Zoom</a></li>
                                <li><a class="dropdown-item zoom-opt" href="#" data-zoom="0.5">50% Overview</a></li>
                            </ul>
                        </div>

                        {{-- Toggle Maximize Height --}}
                        <button type="button" class="btn btn-sm btn-outline-light py-0 px-2 font-size-11" id="btnToggleMaximizeHeight" title="Toggle Full Viewport Height">
                            <i class="bx bx-fullscreen" id="maximizeIcon"></i>
                        </button>
                    </div>
                </div>

                {{-- BROWSER URL BAR & ROUTE SWITCHER --}}
                <div class="bg-light border-bottom p-2 d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-2 flex-grow-1" style="min-width: 220px;">
                        <div class="bg-white rounded-pill px-3 py-1 border d-flex align-items-center gap-2 w-100 shadow-sm">
                            <i class="bx bx-lock-alt text-success font-size-13 flex-shrink-0"></i>
                            <span class="font-size-11 text-muted font-monospace text-truncate" id="urlDisplayBar">
                                {{ url('/') }}
                            </span>
                            <span id="iframeMiniSpinner" class="spinner-border spinner-border-sm text-primary ms-auto d-none" style="width: 13px; height: 13px;" role="status"></span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        {{-- Page Selector --}}
                        <select class="form-select form-select-sm font-size-11 py-1 rounded-pill shadow-sm" id="routeSelector" style="width: auto; cursor: pointer;">
                            <option value="{{ url('/') }}" selected>Home (/)</option>
                            <option value="{{ url('/products') }}">Menu / Products (/products)</option>
                            <option value="{{ url('/about') }}">About Us (/about)</option>
                            <option value="{{ url('/contact') }}">Contact Us (/contact)</option>
                        </select>

                        {{-- Reload Frame --}}
                        <button type="button" class="btn btn-sm btn-light border py-1 px-2 font-size-11 rounded-pill shadow-sm" id="btnReloadFrame" title="Reload Storefront Preview">
                            <i class="bx bx-refresh"></i>
                        </button>

                        {{-- Pop Out in Real Tab --}}
                        <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-dark py-1 px-2 font-size-11 rounded-pill d-flex align-items-center gap-1 shadow-sm" id="btnPopOutLink" title="Open storefront in a new browser tab">
                            <i class="bx bx-link-external"></i> <span class="d-none d-sm-inline">Pop Out</span>
                        </a>
                    </div>
                </div>

                {{-- EMULATOR VIEWPORT STAGE --}}
                <div class="bg-light d-flex align-items-center justify-content-center p-3 position-relative" id="emulatorStageContainer" style="min-height: 700px; height: calc(100vh - 240px); overflow: auto; transition: height 0.3s ease;">
                    {{-- DEVICE FRAME WRAPPER --}}
                    <div id="deviceFrameWrapper" class="w-100 h-100 transition-all position-relative" style="background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); transition: all 0.3s ease;">
                        <iframe src="{{ url('/') }}" 
                                id="storefrontPreviewIframe"
                                class="w-100 h-100 border-0" 
                                style="width: 100%; height: 100%; display: block;"
                                allowfullscreen>
                        </iframe>
                    </div>
                </div>

                {{-- EMULATOR FOOTER HELPER --}}
                <div class="card-footer bg-white border-top py-2 px-3 d-flex align-items-center justify-content-between font-size-11 text-muted">
                    <div>
                        <i class="bx bx-info-circle me-1 text-primary"></i> 
                        <span id="deviceDimensionIndicator">Desktop Mode (Fluid 100%)</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-success"><i class="bx bx-check-circle me-1"></i>Real Live Emulation</span>
                        <span>&bull;</span>
                        <a href="javascript:void(0);" id="btnTriggerManualSync" class="text-primary text-decoration-none fw-semibold">
                            <i class="bx bx-sync me-1"></i>Sync Edits Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputSiteName = document.getElementById('inputSiteName');
    const inputDesc = document.getElementById('inputDescription');
    const siteNameCount = document.getElementById('siteNameCount');
    const descCount = document.getElementById('descriptionCount');

    const fileInputLogo = document.getElementById('fileInputLogo');
    const fileInputFavicon = document.getElementById('fileInputFavicon');
    const previewLogoImg = document.getElementById('previewLogoImg');
    const previewFaviconImg = document.getElementById('previewFaviconImg');
    const logoFileDetails = document.getElementById('logoFileDetails');
    const faviconFileDetails = document.getElementById('faviconFileDetails');

    const logoDropzone = document.getElementById('logoDropzone');
    const faviconDropzone = document.getElementById('faviconDropzone');

    // Folding Elements
    const editorCol = document.getElementById('editorCol');
    const previewCol = document.getElementById('previewCol');
    const editorDockRail = document.getElementById('editorDockRail');
    const btnFoldEditor = document.getElementById('btnFoldEditor');
    const btnRailUnfold = document.getElementById('btnRailUnfold');
    const btnUnfoldEditorTop = document.getElementById('btnUnfoldEditorTop');

    // Emulation Elements
    const storefrontIframe = document.getElementById('storefrontPreviewIframe');
    const deviceFrameWrapper = document.getElementById('deviceFrameWrapper');
    const deviceButtons = document.querySelectorAll('.device-btn');
    const deviceDimensionIndicator = document.getElementById('deviceDimensionIndicator');
    const btnReloadFrame = document.getElementById('btnReloadFrame');
    const routeSelector = document.getElementById('routeSelector');
    const urlDisplayBar = document.getElementById('urlDisplayBar');
    const btnPopOutLink = document.getElementById('btnPopOutLink');
    const iframeMiniSpinner = document.getElementById('iframeMiniSpinner');
    const btnToggleMaximizeHeight = document.getElementById('btnToggleMaximizeHeight');
    const maximizeIcon = document.getElementById('maximizeIcon');
    const emulatorStageContainer = document.getElementById('emulatorStageContainer');
    const zoomOptions = document.querySelectorAll('.zoom-opt');
    const btnZoomLabel = document.getElementById('btnZoomLabel');
    const btnTriggerManualSync = document.getElementById('btnTriggerManualSync');

    let currentLogoBase64 = null;
    let currentFaviconBase64 = null;
    let isMaximizedHeight = false;

    // =========================================================================
    // 1. FOLDING EDITOR TOWARD SIDEBAR (EXPAND STOREFRONT TO WIDE SCREEN)
    // =========================================================================
    function foldEditor() {
        if (!editorCol || !previewCol) return;

        editorCol.classList.add('d-none');
        if (editorDockRail) {
            editorDockRail.classList.remove('d-none');
            editorDockRail.classList.add('d-flex');
        }

        previewCol.classList.remove('col-xl-6', 'col-lg-6');
        previewCol.classList.add('col-xl-12', 'col-lg-12');

        if (btnUnfoldEditorTop) {
            btnUnfoldEditorTop.classList.remove('d-none');
            btnUnfoldEditorTop.classList.add('d-inline-flex');
        }

        try {
            localStorage.setItem('moshel_sitesettings_editor_folded', 'true');
        } catch (e) {}
    }

    function unfoldEditor() {
        if (!editorCol || !previewCol) return;

        editorCol.classList.remove('d-none');
        if (editorDockRail) {
            editorDockRail.classList.remove('d-flex');
            editorDockRail.classList.add('d-none');
        }

        previewCol.classList.remove('col-xl-12', 'col-lg-12');
        previewCol.classList.add('col-xl-6', 'col-lg-6');

        if (btnUnfoldEditorTop) {
            btnUnfoldEditorTop.classList.remove('d-inline-flex');
            btnUnfoldEditorTop.classList.add('d-none');
        }

        try {
            localStorage.setItem('moshel_sitesettings_editor_folded', 'false');
        } catch (e) {}
    }

    if (btnFoldEditor) btnFoldEditor.addEventListener('click', foldEditor);
    if (btnRailUnfold) btnRailUnfold.addEventListener('click', unfoldEditor);
    if (btnUnfoldEditorTop) btnUnfoldEditorTop.addEventListener('click', unfoldEditor);

    // Restore folding preference
    try {
        if (localStorage.getItem('moshel_sitesettings_editor_folded') === 'true') {
            foldEditor();
        }
    } catch (e) {}

    // =========================================================================
    // 2. LIVE STOREFRONT EMULATOR: REAL-TIME DOM SYNC BRIDGE
    // =========================================================================
    function syncFormToIframe() {
        if (!storefrontIframe) return;

        try {
            const iframeDoc = storefrontIframe.contentDocument || storefrontIframe.contentWindow?.document;
            if (!iframeDoc) return;

            const nameVal = (inputSiteName ? inputSiteName.value.trim() : '') || 'Moshel Edibles';
            const descVal = (inputDesc ? inputDesc.value.trim() : '');

            // 1. Update Title and Meta
            iframeDoc.title = nameVal + ' | Store';
            const metaTitle = iframeDoc.querySelector('meta[name="title"]');
            if (metaTitle) metaTitle.setAttribute('content', nameVal);

            // 2. Update Navbar & Footer Brand Logos
            const allLogos = iframeDoc.querySelectorAll('.navbar-brand img, .footer-logo img, img[alt*="Logo"], img[alt*="logo"]');
            allLogos.forEach(img => {
                img.alt = nameVal;
                if (currentLogoBase64) {
                    img.src = currentLogoBase64;
                }
            });

            // 3. Update Brand text if present
            const brandTexts = iframeDoc.querySelectorAll('.navbar-brand span, .brand-title, .site-name-text');
            brandTexts.forEach(el => el.textContent = nameVal);

            // 4. Update Favicon if selected
            if (currentFaviconBase64) {
                let favLink = iframeDoc.querySelector('link[rel="shortcut icon"], link[rel="icon"]');
                if (!favLink) {
                    favLink = iframeDoc.createElement('link');
                    favLink.rel = 'shortcut icon';
                    iframeDoc.head.appendChild(favLink);
                }
                favLink.href = currentFaviconBase64;
            }

            // Visual pulse on Live Sync badge
            const syncBadge = document.getElementById('liveSyncStatusBadge');
            if (syncBadge) {
                syncBadge.classList.add('bg-success', 'text-white');
                setTimeout(() => {
                    syncBadge.classList.remove('bg-success', 'text-white');
                }, 500);
            }
        } catch (err) {
            // Silently ignore cross-origin restrictions if navigates off-domain
            console.debug('Storefront live sync note:', err);
        }
    }

    if (storefrontIframe) {
        storefrontIframe.addEventListener('load', function () {
            if (iframeMiniSpinner) iframeMiniSpinner.classList.add('d-none');
            syncFormToIframe();
        });
    }

    if (btnTriggerManualSync) {
        btnTriggerManualSync.addEventListener('click', function (e) {
            e.preventDefault();
            syncFormToIframe();
        });
    }

    // =========================================================================
    // 3. FORM INPUT COUNTERS & LIVE PROPAGATION
    // =========================================================================
    function updateTextPreviews() {
        if (siteNameCount && inputSiteName) {
            siteNameCount.textContent = `${inputSiteName.value.length} / 60`;
        }
        if (descCount && inputDesc) {
            descCount.textContent = `${inputDesc.value.length} / 255`;
        }
        syncFormToIframe();
    }

    if (inputSiteName) inputSiteName.addEventListener('input', updateTextPreviews);
    if (inputDesc) inputDesc.addEventListener('input', updateTextPreviews);
    updateTextPreviews();

    // =========================================================================
    // 4. CLIENT-SIDE IMAGE SELECTION & LIVE PREVIEW
    // =========================================================================
    function handleImageSelection(file, previewImg, detailsBadge, isLogo) {
        if (!file) return;

        if (!file.type.startsWith('image/') && !file.name.endsWith('.ico')) {
            alert('Please select a valid image file (PNG, JPG, SVG, or ICO).');
            return;
        }

        const sizeKb = (file.size / 1024).toFixed(1);
        const reader = new FileReader();

        reader.onload = function (e) {
            const resultUrl = e.target.result;
            if (previewImg) previewImg.src = resultUrl;

            if (isLogo) {
                currentLogoBase64 = resultUrl;
            } else {
                currentFaviconBase64 = resultUrl;
            }

            if (detailsBadge) {
                detailsBadge.className = 'badge bg-success-subtle text-success font-size-10';
                detailsBadge.innerHTML = `<i class="bx bx-check me-1"></i>Selected: ${file.name} (${sizeKb} KB)`;
            }

            syncFormToIframe();
        };

        reader.readAsDataURL(file);
    }

    if (fileInputLogo) {
        fileInputLogo.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                handleImageSelection(this.files[0], previewLogoImg, logoFileDetails, true);
            }
        });
    }

    if (fileInputFavicon) {
        fileInputFavicon.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                handleImageSelection(this.files[0], previewFaviconImg, faviconFileDetails, false);
            }
        });
    }

    // Drag and Drop & Click to Select
    function setupDropzone(dropzoneEl, fileInputEl) {
        if (!dropzoneEl || !fileInputEl) return;

        // Click anywhere inside dropzone triggers the file picker (unless clicking a button or input directly)
        dropzoneEl.addEventListener('click', function (e) {
            if (e.target !== fileInputEl && !e.target.closest('button')) {
                fileInputEl.click();
            }
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzoneEl.addEventListener(eventName, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropzoneEl.classList.add('border-primary', 'bg-white');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzoneEl.addEventListener(eventName, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropzoneEl.classList.remove('border-primary', 'bg-white');
            }, false);
        });

        dropzoneEl.addEventListener('drop', function (e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                fileInputEl.files = files;
                fileInputEl.dispatchEvent(new Event('change'));
            }
        }, false);
    }

    setupDropzone(logoDropzone, fileInputLogo);
    setupDropzone(faviconDropzone, fileInputFavicon);

    // =========================================================================
    // 5. DEVICE VIEWPORT SWITCHER (DESKTOP, LAPTOP, TABLET, MOBILE)
    // =========================================================================
    deviceButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            deviceButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const device = this.getAttribute('data-device');
            applyDeviceEmulation(device);
        });
    });

    function applyDeviceEmulation(device) {
        if (!deviceFrameWrapper || !deviceDimensionIndicator) return;

        deviceFrameWrapper.style.transition = 'all 0.35s cubic-bezier(0.4, 0, 0.2, 1)';

        switch (device) {
            case 'laptop':
                deviceFrameWrapper.style.maxWidth = '1024px';
                deviceFrameWrapper.style.margin = '0 auto';
                deviceFrameWrapper.style.border = '2px solid #cbd5e1';
                deviceFrameWrapper.style.borderRadius = '12px';
                deviceFrameWrapper.style.boxShadow = '0 10px 25px rgba(0,0,0,0.1)';
                deviceDimensionIndicator.innerHTML = '<i class="bx bx-laptop me-1 text-primary"></i> Laptop Viewport (1024px Screen Standard)';
                break;
            case 'tablet':
                deviceFrameWrapper.style.maxWidth = '768px';
                deviceFrameWrapper.style.margin = '0 auto';
                deviceFrameWrapper.style.border = '10px solid #334155';
                deviceFrameWrapper.style.borderRadius = '24px';
                deviceFrameWrapper.style.boxShadow = '0 15px 35px rgba(0,0,0,0.18)';
                deviceDimensionIndicator.innerHTML = '<i class="bx bx-tab me-1 text-info"></i> Tablet Viewport (768px iPad Aspect)';
                break;
            case 'mobile':
                deviceFrameWrapper.style.maxWidth = '390px';
                deviceFrameWrapper.style.margin = '0 auto';
                deviceFrameWrapper.style.border = '12px solid #1e293b';
                deviceFrameWrapper.style.borderRadius = '36px';
                deviceFrameWrapper.style.boxShadow = '0 20px 40px rgba(0,0,0,0.22)';
                deviceDimensionIndicator.innerHTML = '<i class="bx bx-mobile-alt me-1 text-success"></i> Smartphone Viewport (390px Mobile Responsive)';
                break;
            case 'desktop':
            default:
                deviceFrameWrapper.style.maxWidth = '100%';
                deviceFrameWrapper.style.margin = '0';
                deviceFrameWrapper.style.border = 'none';
                deviceFrameWrapper.style.borderRadius = '8px';
                deviceFrameWrapper.style.boxShadow = '0 4px 15px rgba(0,0,0,0.06)';
                deviceDimensionIndicator.innerHTML = '<i class="bx bx-desktop me-1 text-primary"></i> Desktop Viewport (Fluid 100% Full Width)';
                break;
        }
    }

    // =========================================================================
    // 6. ZOOM & SCALE SELECTOR
    // =========================================================================
    zoomOptions.forEach(opt => {
        opt.addEventListener('click', function (e) {
            e.preventDefault();
            zoomOptions.forEach(o => o.classList.remove('active'));
            this.classList.add('active');

            const zoomVal = parseFloat(this.getAttribute('data-zoom')) || 1;
            if (btnZoomLabel) btnZoomLabel.textContent = `${Math.round(zoomVal * 100)}%`;

            if (storefrontIframe) {
                storefrontIframe.style.transform = zoomVal === 1 ? 'none' : `scale(${zoomVal})`;
                storefrontIframe.style.transformOrigin = 'top center';
                storefrontIframe.style.width = zoomVal === 1 ? '100%' : `${100 / zoomVal}%`;
                storefrontIframe.style.height = zoomVal === 1 ? '100%' : `${100 / zoomVal}%`;
            }
        });
    });

    // =========================================================================
    // 7. ROUTE SWITCHER & RELOAD
    // =========================================================================
    if (routeSelector && storefrontIframe) {
        routeSelector.addEventListener('change', function () {
            const targetUrl = this.value;
            if (iframeMiniSpinner) iframeMiniSpinner.classList.remove('d-none');

            storefrontIframe.src = targetUrl;
            if (urlDisplayBar) urlDisplayBar.textContent = targetUrl;
            if (btnPopOutLink) btnPopOutLink.href = targetUrl;
        });
    }

    if (btnReloadFrame && storefrontIframe) {
        btnReloadFrame.addEventListener('click', function () {
            if (iframeMiniSpinner) iframeMiniSpinner.classList.remove('d-none');
            try {
                storefrontIframe.contentWindow?.location.reload();
            } catch (e) {
                storefrontIframe.src = storefrontIframe.src;
            }
        });
    }

    // =========================================================================
    // 8. MAXIMIZE HEIGHT TOGGLE
    // =========================================================================
    if (btnToggleMaximizeHeight && emulatorStageContainer) {
        btnToggleMaximizeHeight.addEventListener('click', function () {
            isMaximizedHeight = !isMaximizedHeight;
            if (isMaximizedHeight) {
                emulatorStageContainer.style.height = 'calc(100vh - 100px)';
                if (maximizeIcon) maximizeIcon.className = 'bx bx-exit-fullscreen';
                btnToggleMaximizeHeight.classList.add('active');
            } else {
                emulatorStageContainer.style.height = 'calc(100vh - 240px)';
                if (maximizeIcon) maximizeIcon.className = 'bx bx-fullscreen';
                btnToggleMaximizeHeight.classList.remove('active');
            }
        });
    }

    // =========================================================================
    // 9. SUBMIT BUTTON SPINNER
    // =========================================================================
    const form = document.getElementById('siteConfigForm');
    const btnSave = document.getElementById('btnSaveConfig');
    if (form && btnSave) {
        form.addEventListener('submit', function () {
            btnSave.disabled = true;
            btnSave.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Saving Configuration...';
        });
    }
});
</script>
@endsection

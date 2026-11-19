{{-- ========================================================================= --}}
{{-- RIGHT COLUMN: REAL LANDING PAGE LIVE EMULATION                            --}}
{{-- ========================================================================= --}}
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

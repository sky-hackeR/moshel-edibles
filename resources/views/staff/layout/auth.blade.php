<!doctype html>
<html lang="en" data-layout-mode="light">

<head>
    <meta charset="utf-8" />
    <title>{{ !empty($pageGlobalData->setting?->site_name) ? $pageGlobalData->setting->site_name : "Moshel Edibles" }} - Staff Workstation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="{{ !empty($pageGlobalData->setting?->description) ? $pageGlobalData->setting->description : 'Staff Workstation & Food Production Terminal' }}" name="description" />
    
    <link rel="shortcut icon" href="{{ !empty($pageGlobalData->setting?->favicon) ? asset($pageGlobalData->setting->favicon) : asset('favicon.ico') }}">

    <!-- Bootstrap & Core Styles -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <style>
        :root {
            --staff-emerald-dark: #064e3b;
            --staff-emerald: #059669;
            --staff-emerald-light: #10b981;
            --staff-accent: #f59e0b;
        }

        body.staff-auth-body {
            background-color: #f8fafc;
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* LEFT BRAND SHOWCASE PANEL */
        .staff-showcase-panel {
            background: radial-gradient(circle at 15% 20%, rgba(255, 255, 255, 0.25) 0%, transparent 40%),
                        radial-gradient(circle at 85% 80%, rgba(245, 158, 11, 0.15) 0%, transparent 45%),
                        linear-gradient(135deg, #43973bff 0%, #065f46 50%, #047857 100%);
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        .staff-showcase-panel::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
            opacity: 0.6;
        }

        .staff-feature-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 14px;
            padding: 1.15rem 1.25rem;
            transition: transform 0.25s ease, background 0.25s ease;
        }

        .staff-feature-card:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.12);
        }

        .staff-feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        /* RIGHT FORM PANEL */
        .staff-form-container {
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2.5rem 3rem;
            position: relative;
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.03);
        }

        @media (max-width: 991.98px) {
            .staff-form-container {
                padding: 2rem 1.5rem;
                min-height: 100vh;
            }
        }

        .staff-form-card {
            width: 100%;
            max-width: 440px;
            margin: auto;
        }

        .staff-input-group {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .staff-input-group:focus-within {
            border-color: var(--staff-emerald);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.12);
        }

        .staff-input-group .input-group-text {
            background: transparent;
            border: none;
            color: #64748b;
        }

        .staff-input-group .form-control {
            background: transparent;
            border: none;
            font-size: 0.9rem;
            padding: 0.65rem 0.5rem 0.65rem 0;
        }

        .staff-input-group .form-control:focus {
            box-shadow: none;
            outline: none;
        }

        .btn-staff-primary {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            border: none;
            color: #ffffff;
            font-weight: 600;
            border-radius: 10px;
            padding: 0.75rem 1.25rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
        }

        .btn-staff-primary:hover {
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(5, 150, 105, 0.35);
        }

        .btn-staff-primary:active {
            transform: translateY(0);
        }

        .bg-white-subtle {
            background-color: rgba(255, 255, 255, 0.12);
        }
        .border-white-subtle {
            border-color: rgba(255, 255, 255, 0.2) !important;
        }
    </style>
</head>

<body class="staff-auth-body">
    @include('sweetalert::alert')

    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-100">

            {{-- =====================================================
                 LEFT BRAND SHOWCASE & TERMINAL OVERVIEW (LARGE SCREENS)
                 ===================================================== --}}
            <div class="col-lg-7 col-xl-8 d-none d-lg-block">
                <div class="staff-showcase-panel p-5">
                    {{-- TOP BRAND HEADER --}}
                    <div class="position-relative" style="z-index: 2;">
                        <div class="d-flex align-items-center gap-3">
                            @if(!empty($pageGlobalData->setting?->logo))
                                <!-- <img src="{{ asset($pageGlobalData->setting->logo) }}" alt="Logo" style="height: 42px; width: auto; max-width: 180px; object-fit: contain;"> -->
                            @else
                                <div class="rounded-3 bg-white p-2 d-inline-flex align-items-center justify-content-center shadow-xs" style="width: 44px; height: 44px;">
                                    <i class="bx bx-store-alt font-size-24 text-success"></i>
                                </div>
                                <span class="fw-bold text-white font-size-20" style="letter-spacing: -0.02em;">
                                    {{ $pageGlobalData->setting->site_name ?? 'Moshel Edibles' }}
                                </span>
                            @endif
                            <span class="badge bg-white-subtle text-white border border-white-subtle rounded-pill font-size-11 px-3 py-1">
                                Staff Workstation
                            </span>
                        </div>
                    </div>

                    {{-- CENTER SHOWCASE & HIGHLIGHT CARDS --}}
                    <div class="position-relative my-auto py-5" style="z-index: 2; max-width: 660px;">
                        <span class="badge bg-emerald-subtle text-white border border-white-subtle rounded-pill px-3 py-1 font-size-12 fw-semibold mb-3 d-inline-flex align-items-center gap-1"
                              style="background: rgba(16, 185, 129, 0.25);">
                            <span style="width: 7px; height: 7px; border-radius: 50%; background: #34d399;"></span> Food Production &amp; POS Systems
                        </span>

                        <h1 class="text-white fw-bold mb-3" style="font-size: 2.35rem; line-height: 1.25; letter-spacing: -0.025em;">
                            Powering daily baking excellence and instant counter checkout.
                        </h1>

                        <p class="text-white-50 font-size-15 mb-4" style="line-height: 1.65;">
                            Welcome to the staff terminal. Process customer orders at the POS, record recipe baking batches with automatic stock deductions, and verify raw ingredient levels.
                        </p>

                        {{-- 3 CAPABILITY TILES --}}
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="staff-feature-card h-100">
                                    <div class="staff-feature-icon bg-white text-success mb-2">
                                        <i class="bx bx-shopping-bag"></i>
                                    </div>
                                    <h6 class="text-white fw-bold font-size-14 mb-1">Fast POS Terminal</h6>
                                    <p class="text-white-50 font-size-12 mb-0">Multi-payment cashier register &amp; instant receipts.</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="staff-feature-card h-100">
                                    <div class="staff-feature-icon bg-white text-primary mb-2">
                                        <i class="bx bx-dish"></i>
                                    </div>
                                    <h6 class="text-white fw-bold font-size-14 mb-1">Bakery Production</h6>
                                    <p class="text-white-50 font-size-12 mb-0">Log completed batches with auto ingredient deduction.</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="staff-feature-card h-100">
                                    <div class="staff-feature-icon bg-white text-warning mb-2">
                                        <i class="bx bx-check-shield"></i>
                                    </div>
                                    <h6 class="text-white fw-bold font-size-14 mb-1">Stock Vigilance</h6>
                                    <p class="text-white-50 font-size-12 mb-0">Live flour, sugar &amp; pantry ingredient buffers.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BOTTOM FOOTER PILL --}}
                    <div class="position-relative pt-3 border-top border-white-subtle d-flex align-items-center justify-content-between text-white-50 font-size-12" style="z-index: 2;">
                        <span><i class="bx bx-shield-quarter text-success me-1"></i> Authorized Staff Access Only · Confidential Session</span>
                    </div>
                </div>
            </div>


            {{-- =====================================================
                 RIGHT AUTHENTICATION FORM CONTAINER
                 ===================================================== --}}
            <div class="col-lg-5 col-xl-4">
                <div class="staff-form-container">
                    {{-- TOP HEADER FOR MOBILE (Hidden on large screens when left hero is visible) --}}
                    <div class="d-flex d-lg-none align-items-center justify-content-between mb-4">
                        <a href="{{ url('/') }}" class="text-decoration-none d-flex align-items-center gap-2">
                            @if(!empty($pageGlobalData->setting?->favicon))
                                <img src="{{ asset($pageGlobalData->setting->favicon) }}" alt="Logo" height="28">
                            @else
                                <div class="rounded-2 bg-success text-white p-1 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                    <i class="bx bx-store font-size-16"></i>
                                </div>
                            @endif
                        </a>

                        <a href="{{ url('/') }}" class="text-muted font-size-12 text-decoration-none d-inline-flex align-items-center gap-1">
                            <i class="bx bx-left-arrow-alt"></i> Storefront
                        </a>
                    </div>

                    {{-- CENTER FORM INJECTION --}}
                    <div class="staff-form-card my-auto">
                        @yield('content')
                    </div>

                    {{-- FOOTER COPYRIGHT --}}
                    <div class="text-center pt-4 text-muted font-size-12">
                        <p class="mb-0">
                            © <script>document.write(new Date().getFullYear())</script> {{ $pageGlobalData->setting->site_name ?? 'Moshel Edibles' }}.
                            <span class="d-block text-muted opacity-75 font-size-11 mt-0.5">Staff Cashier &amp; Kitchen Production Terminal</span>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Core Scripts -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>

</html>

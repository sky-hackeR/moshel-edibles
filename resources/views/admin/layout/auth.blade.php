<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Admin Authentication') - {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : 'Moshel' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->description : 'Production & Inventory Management' }}" name="description" />
    <meta content="skyhackeR" name="author" />

    {{-- App favicon --}}
    <link rel="shortcut icon" href="{{ !empty($pageGlobalData->setting) ? asset($pageGlobalData->setting->favicon) : asset('assets/images/favicon.ico') }}">

    {{-- Bootstrap & App Styles --}}
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    {{-- Inter Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body.admin-auth-body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 50%, #e2e8f0 100%);
            min-height: 100vh;
            color: #1e293b;
        }

        .admin-auth-card {
            border: 1px solid rgba(226, 232, 240, 0.95);
            border-radius: 1rem;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.08), 0 0 1px 1px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            background: #ffffff;
        }

        .admin-auth-banner {
            background: linear-gradient(135deg, rgba(85, 110, 230, 0.12) 0%, rgba(85, 110, 230, 0.04) 100%);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        .admin-auth-avatar {
            width: 72px;
            height: 72px;
            margin-top: -36px;
            background: #ffffff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            border: 3px solid #ffffff;
            position: relative;
            z-index: 2;
        }

        .admin-auth-input-group {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            background: #f8fafc;
            transition: all 0.2s ease;
        }
        .admin-auth-input-group:focus-within {
            border-color: #556ee6;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(85, 110, 230, 0.12);
        }
        .admin-auth-input-group .input-group-text {
            background: transparent;
            border: none;
            color: #64748b;
        }
        .admin-auth-input-group .form-control {
            border: none;
            background: transparent;
            box-shadow: none;
            font-size: 0.875rem;
            color: #1e293b;
            padding: 0.65rem 0.75rem;
        }
        .admin-auth-input-group .form-control:focus {
            box-shadow: none;
            background: transparent;
        }

        .btn-admin-primary {
            background: linear-gradient(135deg, #556ee6 0%, #4458b8 100%);
            border: none;
            color: #ffffff;
            font-weight: 600;
            letter-spacing: 0.02em;
            padding: 0.7rem 1.25rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(85, 110, 230, 0.25);
        }
        .btn-admin-primary:hover {
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(85, 110, 230, 0.35);
        }
        .btn-admin-primary:active {
            transform: translateY(0);
        }
    </style>
    @stack('styles')
</head>

<body class="admin-auth-body">
    @include('sweetalert::alert')

    <div class="account-pages py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    
                    {{-- MAIN CARD WRAPPER --}}
                    <div class="card admin-auth-card">
                        @yield('content')
                    </div>

                    {{-- FOOTER INFO & STOREFRONT RETURN --}}
                    <div class="mt-4 text-center">
                        <div class="mb-2">
                            <a href="{{ url('/') }}" class="text-muted font-size-13 text-decoration-none d-inline-flex align-items-center gap-1 hover-primary">
                                <i class="bx bx-store-alt"></i> Return to Storefront
                            </a>
                        </div>
                        <p class="text-muted font-size-12 mb-0">
                            © <script>document.write(new Date().getFullYear())</script> {{ $pageGlobalData->setting->site_name ?? 'Moshel Edibles' }}. 
                            <span class="d-block text-muted opacity-75 font-size-11 mt-0.5">Administrative ERP &amp; Management Control</span>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- end account-pages -->

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    
    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>

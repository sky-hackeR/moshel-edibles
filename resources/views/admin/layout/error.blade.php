<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Admin Error') - {{ $pageGlobalData->setting->site_name ?? 'Moshel' }} Management Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="System Administration Error" name="description" />
    <meta content="skyhackeR" name="author" />

    <link rel="shortcut icon" href="{{ !empty($pageGlobalData->setting?->favicon) ? asset($pageGlobalData->setting->favicon) : asset('assets/images/favicon.ico') }}">

    {{-- CORE STYLES --}}
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    {{-- GOOGLE FONTS (Inter) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --admin-primary: #556ee6;
            --admin-primary-dark: #4458b8;
            --admin-dark: #222736;
        }

        body.admin-error-body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 50%, #f1f5f9 100%);
            min-height: 100vh;
            color: #1e293b;
            display: flex;
            flex-direction: column;
        }

        .admin-error-navbar {
            padding: 1.25rem 2rem;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.85);
        }

        .admin-error-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.95);
            border-radius: 1.25rem;
            box-shadow: 0 20px 40px -15px rgba(85, 110, 230, 0.1), 0 0 1px 1px rgba(0, 0, 0, 0.02);
            padding: 2.75rem 2.25rem;
            text-align: center;
        }

        .admin-error-code {
            font-size: 5rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.05em;
        }

        .admin-error-code.error-404 {
            background: linear-gradient(135deg, #4458b8 0%, #556ee6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .admin-error-code.error-500 {
            background: linear-gradient(135deg, #dc2626 0%, #ea580c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-admin-primary {
            background: linear-gradient(135deg, #556ee6 0%, #4458b8 100%);
            color: #ffffff;
            border: none;
            font-weight: 600;
            padding: 0.65rem 1.4rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(85, 110, 230, 0.25);
        }
        .btn-admin-primary:hover {
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(85, 110, 230, 0.35);
        }

        .btn-admin-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff;
            border: none;
            font-weight: 600;
            padding: 0.65rem 1.4rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
        }
        .btn-admin-danger:hover {
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.35);
        }

        .admin-error-footer {
            margin-top: auto;
            padding: 1.5rem;
            text-align: center;
            font-size: 0.8125rem;
            color: #64748b;
        }
    </style>
    @stack('styles')
</head>

<body class="admin-error-body">

    {{-- TOP NAVBAR --}}
    <header class="admin-error-navbar">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <a href="{{ auth('admin')->check() ? url('/admin/home') : url('/admin/login') }}" class="text-decoration-none d-flex align-items-center gap-2">
                @if(!empty($pageGlobalData->setting?->favicon))
                    <img src="{{ asset($pageGlobalData->setting->favicon) }}" alt="Logo" height="30">
                @else
                    <div class="rounded-2 bg-primary text-white p-1 d-inline-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                        <i class="bx bx-shield-quarter font-size-18"></i>
                    </div>
                @endif
                <span class="fw-bold text-dark font-size-16" style="letter-spacing: -0.02em;">
                    {{ $pageGlobalData->setting->site_name ?? 'Moshel' }}
                </span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill font-size-11 px-2.5 py-0.5 ms-1">
                    Admin ERP Portal
                </span>
            </a>

            <div class="d-flex align-items-center gap-3 font-size-13">
                @if(auth('admin')->check())
                    <span class="text-muted d-none d-sm-inline-block">
                        <i class="bx bx-user-circle me-1 text-primary"></i> Administrator: <strong>{{ auth('admin')->user()->name }}</strong>
                    </span>
                    <a href="{{ url('/admin/home') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-home-circle me-1"></i> Dashboard
                    </a>
                @else
                    <a href="{{ url('/admin/login') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bx bx-log-in me-1"></i> Admin Sign In
                    </a>
                @endif
                <a href="{{ url('/') }}" class="text-muted text-decoration-none d-inline-flex align-items-center gap-1 font-size-12">
                    <i class="bx bx-store-alt"></i> Storefront
                </a>
            </div>
        </div>
    </header>

    {{-- CENTER ERROR CONTENT --}}
    <main class="my-auto py-5">
        <div class="container">
            @yield('content')
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="admin-error-footer">
        <div class="container">
            <p class="mb-0">
                © <script>document.write(new Date().getFullYear())</script> {{ $pageGlobalData->setting->site_name ?? 'Moshel Edibles' }}. 
                <span class="text-muted">Enterprise Resource Planning &amp; Security Administration.</span>
            </p>
        </div>
    </footer>

    {{-- SCRIPTS --}}
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>

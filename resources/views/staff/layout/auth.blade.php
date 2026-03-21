{{-- <!doctype html>
<html lang="en" data-layout-mode="light">

<head>
    <meta charset="utf-8" />
    <title>{{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : "Staff Portal" }} - Authentication</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->description : 'Staff Login' }}" name="description" />
    
    <link rel="shortcut icon" href="{{ !empty($pageGlobalData->setting) ? asset($pageGlobalData->setting->favicon) : '' }}">

    <link rel="stylesheet" href="{{ asset('assets/libs/owl.carousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/owl.carousel/assets/owl.theme.default.min.css') }}">

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

</head>

<body class="auth-body-bg">
    <div>
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-xl-9">
                    <div class="auth-full-bg pt-lg-5 p-4">
                        <div class="w-100">
                            <div class="bg-overlay"></div>
                            <div class="d-flex h-100 flex-column">
                                <div class="p-4 mt-auto">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-7">
                                            <div class="text-center">
                                                <h4 class="mb-3 text-white"><i class="bx bxs-quote-alt-left text-primary h1 align-middle me-3"></i>Staff Excellence</h4>
                                                <div dir="ltr">
                                                    <div class="owl-carousel owl-theme auth-review-carousel" id="auth-review-carousel">
                                                        <div class="item">
                                                            <div class="py-3">
                                                                <p class="font-size-16 text-white-50 mb-4">"Service is the lifeblood of our organization. Welcome to your workstation."</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="auth-full-page-content p-md-5 p-4">
                        <div class="w-100">
                            <div class="d-flex flex-column h-100">
                                @yield('content')
                                
                                <div class="mt-4 mt-md-5 text-center">
                                    <p class="mb-0">© <script>document.write(new Date().getFullYear())</> {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : 'Company' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/owl.carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/auth-2-carousel.init.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

</body>
</html> --}}

<!doctype html>
<html lang="en" data-layout-mode="light">

<head>
    <meta charset="utf-8" />
    <title>{{ $pageGlobalData->setting->site_name ?? "Staff Portal" }} - Authentication</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="shortcut icon" href="{{ !empty($pageGlobalData->setting->favicon) ? asset($pageGlobalData->setting->favicon) : asset('favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('assets/libs/owl.carousel/assets/owl.carousel.min.css') }}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <style>
        /* Minimalist Mesh Gradient Animation */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .auth-full-bg {
            background: linear-gradient(-45deg, #556ee6, #34c38f, #63439e, #2a3042);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .bg-overlay {
            background: rgba(255, 255, 255, 0.05); /* Very subtle light overlay for glass effect */
            backdrop-filter: blur(10px);
            position: absolute;
            height: 100%; width: 100%; top: 0; left: 0;
        }

        .auth-full-bg .w-100 { position: relative; z-index: 2; }

        /* Minimalist Typography */
        .auth-review-carousel .item p {
            font-size: 22px;
            font-weight: 300;
            letter-spacing: 0.5px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.9);
        }

        .staff-header {
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        /* Hide Scrollbars and Clean UI */
        .owl-dots { margin-top: 20px !important; }
        .owl-dot span { background: rgba(255,255,255,0.3) !important; }
        .owl-dot.active span { background: #fff !important; width: 30px !important; }
    </style>
</head>

<body class="auth-body-bg">
    @php
        $quotes = [
            "Precision in every line of code.",
            "Designing the future of service excellence.",
            "Your workspace, optimized for performance.",
            "Innovation is the heart of our operations."
        ];
    @endphp

    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-xl-9 d-none d-xl-block">
                <div class="auth-full-bg vh-100">
                    <div class="bg-overlay"></div>
                    <div class="w-100 p-5">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="text-center">
                                    <h4 class="staff-header text-white">
                                        <i class="bx bxs-circle text-primary me-2"></i> Staff Portal
                                    </h4>
                                    <div dir="ltr">
                                        <div class="owl-carousel owl-theme auth-review-carousel" id="auth-review-carousel">
                                            @foreach($quotes as $quote)
                                                <div class="item">
                                                    <p>"{{ $quote }}"</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="auth-full-page-content p-md-5 p-4 vh-100 d-flex flex-column">
                    <div class="w-100 my-auto">
                        @yield('content')
                    </div>
                    <div class="mt-4 text-center">
                        <p class="mb-0 text-muted" style="font-size: 12px;">
                            © <script>document.write(new Date().getFullYear())</script> 
                            {{ $pageGlobalData->setting->site_name ?? 'Staff Portal' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/owl.carousel/owl.carousel.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#auth-review-carousel").owlCarousel({
                items: 1,
                loop: true,
                autoplay: true,
                autoplayTimeout: 4000,
                smartSpeed: 1000, // Smooth transition speed
                animateIn: 'fadeIn',
                animateOut: 'fadeOut',
                dots: true,
                nav: false
            });
        });
    </script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
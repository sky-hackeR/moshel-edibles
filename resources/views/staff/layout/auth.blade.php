<!doctype html>
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
    <div class="position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
        <div class="form-check form-switch mb-3">
            <input class="form-check-input theme-choice" type="checkbox" id="dark-mode-switch-global">
            <label class="form-check-label fw-bold text-primary" for="dark-mode-switch-global">Dark Mode</label>
        </div>
    </div>

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
</html>
<!doctype html>
<html lang="eng">
    <head>
        <!-- Meta -->
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <meta content="Jolayemi Olugbenga David (sky-hackeR(+2348082574927))" name="author" />


        <!-- Primary SEO Meta Tags -->
        <title>@yield('title') | {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : 'Moshel Edibles' }}</title>
        <meta name="title" content="@yield('title') | {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : 'Moshel Edibles' }}">
        <meta name="description" content="{{ !empty($pageGlobalData->setting->description) ? $pageGlobalData->setting->description : 'Moshel Edibles - Home of yummy tastes. Artisanal treats, cakes, and gourmet bakes made fresh daily.' }}">
        <meta name="keywords" content="Moshel Edibles, Bakery, Custom Cakes, Pastries, Gourmet Treats, Delights, Confectionery">
        <meta name="author" content="{{ !empty($pageGlobalData->setting->site_name) ? $pageGlobalData->setting->site_name : 'Moshel Edibles' }}">
        <meta name="robots" content="index, follow">

        <!-- Open Graph / Facebook SEO -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="@yield('title') | {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : 'Moshel Edibles' }}">
        <meta property="og:description" content="{{ !empty($pageGlobalData->setting->description) ? $pageGlobalData->setting->description : 'Moshel Edibles - Home of yummy tastes. Artisanal treats, cakes, and gourmet bakes made fresh daily.' }}">
        <meta property="og:image" content="{{ !empty($pageGlobalData->setting) ? asset($pageGlobalData->setting->logo) : asset('frontAssets/images/logo.png') }}">

        <!-- Twitter SEO -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url()->current() }}">
        <meta property="twitter:title" content="@yield('title') | {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : 'Moshel Edibles' }}">
        <meta property="twitter:description" content="{{ !empty($pageGlobalData->setting->description) ? $pageGlobalData->setting->description : 'Moshel Edibles - Home of yummy tastes. Artisanal treats, cakes, and gourmet bakes made fresh daily.' }}">
        <meta property="twitter:image" content="{{ !empty($pageGlobalData->setting) ? asset($pageGlobalData->setting->logo) : asset('frontAssets/images/logo.png') }}">


        <!-- Page Title -->
        <title>@yield('title', 'Store') | {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : 'Moshel' }}</title>

        <!-- Favicon Icon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ !empty($pageGlobalData->setting) ? asset($pageGlobalData->setting->favicon) : '' }}">

        <!-- Bootstrap Css -->
        <link href="{{asset('frontAssets/css/bootstrap.min.css')}}" rel="stylesheet" media="screen" />
        <!-- SlickNav Css -->
        <link href="{{asset('frontAssets/css/slicknav.min.css')}}" rel="stylesheet" />
        <!-- Swiper Css -->
        <link rel="stylesheet" href="{{asset('frontAssets/css/swiper-bundle.min.css')}}" />
        <!-- Font Awesome Icon Css-->
        <link href="{{asset('frontAssets/css/all.min.css')}}" rel="stylesheet" media="screen" />
        <!-- Animated Css -->
        <link href="{{ asset('frontAssets/css/animate.css') }}" rel="stylesheet" />
        <!-- Magnific Popup Core Css File -->
        {{-- <link rel="stylesheet" href="{{asset('frontAssets/css/magnific-popup.css')}}" /> --}}
        <!-- Mouse Cursor Css File -->
        <link rel="stylesheet" href="{{asset('frontAssets/css/mousecursor.css')}}" />
        <!-- Main Custom Css -->
        <link href="{{asset('frontAssets/css/custom.css')}}" rel="stylesheet" media="screen" />

        <!-- skY Custom Css -->
        <link href="{{asset('frontAssets/css/sky.css')}}" rel="stylesheet" media="screen" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" />
    

    </head>
    <body>
        <!-- Preloader Start -->
        {{-- <div class="preloader">
            <div class="loading-container">
                <div class="loading"></div>
                <div id="loading-icon"><img src="images/loader.svg" alt="" /></div>
            </div>
        </div> --}}
        <!-- Preloader End -->

        <!-- Header Start -->
        <header class="main-header bg-section">
            <div class="header-sticky">
                <nav class="navbar navbar-expand-lg">
                    <div class="container-fluid">
                        <!-- Logo Start -->
                        <a class="navbar-brand" href="{{url('/')}}">
                            <img src="{{ !empty($pageGlobalData->setting) ? asset($pageGlobalData->setting->logo) : '' }}" alt="Logo" style="height: 75px;" />
                        </a>
                        <!-- Logo End -->

                        <!-- Main Menu Start -->
                        <div class="collapse navbar-collapse main-menu">
                            <div class="nav-menu-wrapper">
                                <ul class="navbar-nav mr-auto" id="menu">
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/about') }}">About Us</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/products') }}">Products</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ route('store.contact') }}">Contact Us</a></li>
                                </ul>
                            </div>

                            <!-- Header Social Links Start -->
                            <div class="header-social-links">
                                <ul>
                                    <li>
                                        <a href="#"><i class="fa-brands fa-dribbble"></i></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Header Social Links End -->

                        </div>
                        <!-- Main Menu End -->
                        <div class="header-btn d-flex align-items-center gap-2">
                            @auth('customer')
                                <div class="dropdown d-inline-block">
                                    <a href="#" class="store-header-account-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Account">
                                        <i class="fa-regular fa-user"></i>
                                        <span class="d-none d-md-inline">{{ \Illuminate\Support\Str::words(auth('customer')->user()->name, 1, '') }}</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="border-radius: 12px; min-width: 180px; padding: 8px 0; margin-top: 10px;">
                                        <li><a class="dropdown-item py-2" href="{{ route('customer.account') }}"><i class="fa-solid fa-receipt me-2 text-muted"></i> My Orders</a></li>
                                        <li><a class="dropdown-item py-2" href="{{ route('customer.account.profile') }}"><i class="fa-regular fa-id-badge me-2 text-muted"></i> Profile</a></li>
                                        <li><a class="dropdown-item py-2" href="{{ route('customer.account.addresses') }}"><i class="fa-solid fa-location-dot me-2 text-muted"></i> Addresses</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('customer.logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item py-2 text-danger">
                                                    <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Sign Out
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <a href="#" class="store-header-account-btn" data-bs-toggle="modal" data-bs-target="#customerLoginModal" aria-label="Sign In" title="Sign In">
                                    <i class="fa-regular fa-user"></i>
                                    <span class="d-none d-md-inline">Sign in</span>
                                </a>
                            @endauth

                            <a href="{{ route('store.cart') }}" class="store-header-cart" aria-label="Open cart" title="Shopping Cart">
                                <i class="fa-solid fa-basket-shopping"></i>
                                <span class="store-cart-count">{{ app(\App\Services\Store\CartService::class)->count() }}</span>
                            </a>
                        </div>
                        <div class="navbar-toggle"></div>
                    </div>
                </nav>
                <div class="responsive-menu"></div>
            </div>
        </header>
        <!-- Header End -->

        @yield('content')

        @guest('customer')
            <div class="modal fade store-auth-modal" id="customerLoginModal" tabindex="-1" aria-labelledby="customerLoginModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><div><span class="store-summary-kicker">Welcome back</span><h2 class="modal-title" id="customerLoginModalLabel">Sign in to Moshel</h2></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body">
                    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                    @if(session('verification_pending'))
                        <div class="alert alert-warning">Your account exists and your password is correct, but the email address has not been verified. Send a verification email to activate the account.</div>
                        <form method="POST" action="{{ route('customer.verification.resend') }}" class="mb-3">@csrf<input type="hidden" name="email" value="{{ session('verification_pending') }}"><button type="submit" class="btn-default w-100">Send verification email</button></form>
                    @elseif(session('verification_sent'))
                        <div class="alert alert-success">A verification link has been sent. Check your email to activate your account.</div>
                        <form method="POST" action="{{ route('customer.verification.resend') }}" class="mb-3">@csrf<input type="hidden" name="email" value="{{ session('verification_email') }}"><button type="submit" class="btn btn-link w-100">Resend verification email</button></form>
                    @elseif(session('customer_not_found'))
                        <div class="alert alert-info">No customer account was found for this email. You can create one now.</div>
                        <p><a href="#" class="store-text-link" data-bs-toggle="modal" data-bs-target="#customerRegisterModal" data-bs-dismiss="modal">Create an account</a></p>
                    @elseif($errors->has('email'))
                        <div class="alert alert-danger">{{ $errors->first('email') }}</div>
                    @endif
                    <form method="POST" action="{{ route('customer.login.submit') }}">@csrf
                        <div class="store-form-field"><label for="modal-login-email">Email address</label><input id="modal-login-email" type="email" name="email" value="{{ old('email', session('verification_email')) }}" required></div>
                        <div class="store-form-field"><label for="modal-login-password">Password</label><input id="modal-login-password" type="password" name="password" required></div>
                        <div class="d-flex justify-content-between align-items-center mb-4"><label class="store-check"><input type="checkbox" name="remember"> Remember me</label><a href="#" class="store-text-link" data-bs-toggle="modal" data-bs-target="#customerPasswordModal" data-bs-dismiss="modal">Forgot password?</a></div>
                        <button type="submit" class="btn-default w-100">Sign in</button>
                    </form>
                    <p class="store-form-footnote">New here? <a href="#" class="store-text-link" data-bs-toggle="modal" data-bs-target="#customerRegisterModal" data-bs-dismiss="modal">Create an account</a></p>
                </div></div></div>
            </div>
            <div class="modal fade store-auth-modal" id="customerRegisterModal" tabindex="-1" aria-labelledby="customerRegisterModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><div><span class="store-summary-kicker">Start your journey</span><h2 class="modal-title" id="customerRegisterModalLabel">Create your account</h2></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body">
                    <form method="POST" action="{{ route('customer.register.submit') }}">@csrf
                        <div class="store-form-field"><label for="modal-register-name">Full name</label><input id="modal-register-name" type="text" name="name" value="{{ old('name') }}" required></div>
                        <div class="store-form-field"><label for="modal-register-email">Email address</label><input id="modal-register-email" type="email" name="email" value="{{ old('email') }}" required></div>
                        <div class="row"><div class="col-md-6"><div class="store-form-field"><label for="modal-register-password">Password</label><input id="modal-register-password" type="password" name="password" required></div></div><div class="col-md-6"><div class="store-form-field"><label for="modal-register-confirmation">Confirm password</label><input id="modal-register-confirmation" type="password" name="password_confirmation" required></div></div></div>
                        <button type="submit" class="btn-default w-100">Create account</button>
                    </form>
                    <p class="store-form-footnote">Already registered? <a href="#" class="store-text-link" data-bs-toggle="modal" data-bs-target="#customerLoginModal" data-bs-dismiss="modal">Sign in</a></p>
                </div></div></div>
            </div>
            <div class="modal fade store-auth-modal" id="customerPasswordModal" tabindex="-1" aria-labelledby="customerPasswordModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><div><span class="store-summary-kicker">Account access</span><h2 class="modal-title" id="customerPasswordModalLabel">Reset your password</h2></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body">
                    <p class="store-modal-copy">Enter your email and we will send you a secure reset link.</p>
                    <form method="POST" action="{{ route('customer.password.email') }}">@csrf<div class="store-form-field"><label for="modal-reset-email">Email address</label><input id="modal-reset-email" type="email" name="email" value="{{ old('email') }}" required></div><button type="submit" class="btn-default w-100">Send reset link</button></form>
                    <p class="store-form-footnote"><a href="#" class="store-text-link" data-bs-toggle="modal" data-bs-target="#customerLoginModal" data-bs-dismiss="modal">Back to sign in</a></p>
                </div></div></div>
            </div>
        @endguest

        <!-- Footer Start -->
        <footer class="main-footer bg-section dark-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <!-- Footer About Start -->
                        <div class="footer-about">
                            <!-- Footer Logo Start -->
                            <div class="footer-logo">
                                <img src="{{ !empty($pageGlobalData->setting) ? asset($pageGlobalData->setting->logo) : '' }}" alt="Logo" style="height: 75px;" />
                            </div>
                            <!-- Footer Logo End -->

                            <!-- Footer Menu Start -->
                            <div class="footer-menu">
                                <ul>
                                    <li><a href="{{url('/')}}">Home</a></li>
                                    <li><a href="{{url('/about')}}">About us</a></li>
                                    <li><a href="{{ route('store.products') }}">Products</a></li>
                                    <li><a href="{{ route('store.cart') }}">Cart</a></li>
                                    <li><a href="{{ route('store.contact') }}">Contact</a></li>
                                </ul>
                            </div>
                            <!-- Footer Menu End -->

                            <!-- Footer Contact Item Start -->
                            <div class="footer-contact-item">
                                <h3><a href="tel:+2348082574927">+234 808 257 4927</a></h3>
                                <p>Lagos, Nigeria · Fresh Kitchen & Doorstep Dispatch</p>
                            </div>
                            <!-- Footer Contact Item End -->
                        </div>
                        <!-- Footer About End -->
                    </div>

                    <div class="col-lg-6">
                        <!-- Footer Newsletter Form Start -->
                        <div class="footer-newsletter-form">
                            <!-- Footer Newsletter Info Start -->
                            <div class="footer-newsletter-info">
                                <h3>Subscribe to Our Kitchen Dispatch</h3>
                                <p>Be first to know about fresh seasonal batches, weekend specials & celebration treats</p>
                            </div>
                            <!-- Footer Newsletter Info End -->

                            <!-- Newsletter Form Start -->
                            <div class="newsletter-form">
                                <form id="newslettersForm" action="{{ route('store.contact.submit') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <input
                                            type="email"
                                            name="email"
                                            class="form-control"
                                            id="mail"
                                            placeholder="Enter your email address..."
                                            required
                                        />
                                        <button type="submit" class="btn-default btn-highlighted">Subscribe</button>
                                    </div>
                                </form>
                            </div>
                            <!-- Newsletter Form End -->

                            <!-- Footer Social Links Start -->
                            <div class="footer-social-links">
                                <h3>Connect With Us:</h3>
                                <ul>
                                    <li>
                                        <a href="https://wa.me/2348082574927" target="_blank" rel="noopener" title="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                                    </li>
                                    <li>
                                        <a href="tel:+2348082574927" title="Call Us"><i class="fa-solid fa-phone"></i></a>
                                    </li>
                                    <li>
                                        <a href="mailto:hello@moshedibles.com" title="Email"><i class="fa-regular fa-envelope"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Footer Social Links End -->
                        </div>
                        <!-- Footer Newsletter Form End -->
                    </div>

                    <div class="col-lg-12">
                        <!-- Footer Copyright Start -->
                        <div class="footer-copyright">
                            <!-- Footer Copyright Text Start -->
                            <div class="footer-copyright-text">
                                <p>Copyright © {{ date('Y') }} {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : 'Moshel Edibles' }}. All Rights Reserved.</p>
                            </div>
                            <!-- Footer Copyright Text End -->

                            <!-- Footer Privacy Policy Start -->
                            <div class="footer-privacy-policy">
                                <ul>
                                    <li><a href="{{ route('store.contact') }}">Custom Orders</a></li>
                                    <li><a href="{{ route('store.about') }}">Our Craft</a></li>
                                </ul>
                            </div>
                            <!-- Footer Privacy Policy End -->
                        </div>
                        <!-- Footer Copyright End -->
                    </div>
                </div>
            </div>
        </footer>
        <!-- Footer End -->

        <!-- Scripts -->
        <script src="{{ asset('frontAssets/js/jquery-3.7.1.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('frontAssets/js/validator.min.js') }}"></script>
        <script src="{{ asset('frontAssets/js/jquery.slicknav.js') }}"></script>
        <script src="{{ asset('frontAssets/js/swiper-bundle.min.js') }}"></script>
        <script src="{{ asset('frontAssets/js/jquery.waypoints.min.js') }}"></script>
        <script src="{{ asset('frontAssets/js/jquery.counterup.min.js') }}"></script>
        <script src="{{ asset('frontAssets/js/SmoothScroll.js') }}"></script>
        <script src="{{ asset('frontAssets/js/parallaxie.js') }}"></script>
        <script src="{{ asset('frontAssets/js/gsap.min.js') }}"></script>
        <script src="{{ asset('frontAssets/js/magiccursor.js') }}"></script>
        <script src="{{ asset('frontAssets/js/SplitText.js') }}"></script>
        <script src="{{ asset('frontAssets/js/ScrollTrigger.min.js') }}"></script>
        <script src="{{ asset('frontAssets/js/jquery.mb.YTPlayer.min.js') }}"></script>
        <script src="{{ asset('frontAssets/js/wow.min.js') }}"></script>
        <script src="{{ asset('frontAssets/js/function.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof jQuery !== 'undefined' && typeof jQuery.fn.magnificPopup !== 'undefined') {
                    $('.product-gallery-item').magnificPopup({
                        type: 'image',
                        gallery: {
                            enabled: true
                        },
                        image: {
                            titleSrc: 'title'
                        },
                        zoom: {
                            enabled: true,
                            duration: 300
                        },
                        removalDelay: 300,
                        mainClass: 'mfp-fade'
                    });
                }
            });
        </script>

        @if(in_array(session('auth_modal'), ['login', 'register'], true))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var authModalId = @json(session('auth_modal') === 'register' ? 'customerRegisterModal' : 'customerLoginModal');
                    var authModal = document.getElementById(authModalId);
                    if (authModal && window.bootstrap) {
                        bootstrap.Modal.getOrCreateInstance(authModal).show();
                    }
                });
            </script>
        @endif
    </body>
</html>

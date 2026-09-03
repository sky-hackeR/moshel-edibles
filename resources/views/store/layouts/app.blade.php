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
        
        <!-- Page Title -->
        <title>{{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : "Moshel" }} - Front-Facing Shop</title>

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
                                    <li class="nav-item"><a class="nav-link" href="blog.html">Blog</a></li>
                                    <li class="nav-item submenu">
                                        <a class="nav-link" href="#">Pages</a>
                                        <ul>
                                            <li class="nav-item">
                                                <a class="nav-link" href="service-single.html">Service Details</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="blog-single.html">Blog Details</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="products.html">Our Products</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="product-single.html">Product Details</a>
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="team.html">Our Team</a></li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="team-single.html">Team Details</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="testimonials.html">Testimonials</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="image-gallery.html">Image Gallery</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="video-gallery.html">Video Gallery</a>
                                            </li>
                                            <li class="nav-item"><a class="nav-link" href="faqs.html">FAQs</a></li>
                                            <li class="nav-item"><a class="nav-link" href="404.html">404</a></li>
                                        </ul>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="contact.html">Contact Us</a></li>
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

                            <!-- Header Btn Start -->
                            <div class="header-btn">
                                <a href="contact.html" class="btn-default">Shop Now</a>
                            </div>
                            <!-- Header Btn End -->
                        </div>
                        <!-- Main Menu End -->
                        <div class="navbar-toggle"></div>
                    </div>
                </nav>
                <div class="responsive-menu"></div>
            </div>
        </header>
        <!-- Header End -->

        @yield('content')

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
                                    <li><a href="services.html">services</a></li>
                                    <li><a href="image-gallery.html">Gallery</a></li>
                                    <li><a href="contact.html">Contact</a></li>
                                </ul>
                            </div>
                            <!-- Footer Menu End -->

                            <!-- Footer Contact Item Start -->
                            <div class="footer-contact-item">
                                <h3><a href="tel:+123456789">(+123) 456-789</a></h3>
                                <p>4517 Washington Ave. Manchester, Kentucky 39495</p>
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
                                <h3>Subscribe To Our Newsletter!</h3>
                                <p>Freshly baked goodness crafted with love in every single bite</p>
                            </div>
                            <!-- Footer Newsletter Info End -->

                            <!-- Newsletter Form Start -->
                            <div class="newsletter-form">
                                <form id="newslettersForm" action="#" method="POST">
                                    <div class="form-group">
                                        <input
                                            type="email"
                                            name="mail"
                                            class="form-control"
                                            id="mail"
                                            placeholder="Enter Your Email Address *"
                                            required
                                        />
                                        <button type="submit" class="btn-default btn-highlighted">Subscribe Now</button>
                                    </div>
                                </form>
                            </div>
                            <!-- Newsletter Form End -->

                            <!-- Footer Social Links Start -->
                            <div class="footer-social-links">
                                <h3>Follow Us On Socials:</h3>
                                <ul>
                                    <li>
                                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa-brands fa-dribbble"></i></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
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
                                <p>Copyright © 2025 All Rights Reserved.</p>
                            </div>
                            <!-- Footer Copyright Text End -->

                            <!-- Footer Privacy Policy Start -->
                            <div class="footer-privacy-policy">
                                <ul>
                                    <li><a href="#">Privacy Policy</a></li>
                                    <li><a href="#">Legal Information</a></li>
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


        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof jQuery !== 'undefined') {
                    var productIds = [];
                    
                    $('.product-gallery-item').each(function () {
                        var id = $(this).data('product-id');
                        if (id && $.inArray(id, productIds) === -1) {
                            productIds.push(id);
                        }
                    });

                    $.each(productIds, function (index, id) {
                        $('[data-product-id="' + id + '"]').magnificPopup({
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
                    });
                }
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof jQuery === 'undefined' || !$.fn.magnificPopup) return;

                // Efficient delegation: Binds once per card container on demand
                $('.store-product-card').each(function () {
                    var $card = $(this);
                    
                    $card.magnificPopup({
                        delegate: '.product-gallery-item',
                        type: 'image',
                        gallery: {
                            enabled: true,
                            navigateByImgClick: true,
                            preload: [0, 1] // Efficient preloading: Preloads current + 1 next image only
                        },
                        image: {
                            titleSrc: 'title'
                        },
                        removalDelay: 150, // Faster opening/closing animation time
                        mainClass: 'mfp-fade'
                    });
                });
            });
        </script>

        <script src="{{ asset('frontAssets/js/jquery-3.7.1.min.js') }}"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

        <!-- Jquery Library File -->
        <!-- Bootstrap js file -->
        <script src="{{ asset('frontAssets/js/bootstrap.min.js') }}"></script>
        <!-- Validator js file -->
        <script src="{{ asset('frontAssets/js/validator.min.js') }}"></script>
        <!-- SlickNav js file -->
        <script src="{{ asset('frontAssets/js/jquery.slicknav.js') }}"></script>
        <!-- Swiper js file -->
        <script src="{{asset('frontAssets/js/swiper-bundle.min.js')}}"></script>
        <!-- Counter js file -->
        <script src="{{asset('frontAssets/js/jquery.waypoints.min.js')}}"></script>
        <script src="{{asset('frontAssets/js/jquery.counterup.min.js')}}"></script>
        <!-- Magnific js file -->
        {{-- <script src="{{asset('frontAssets/js/jquery.magnific-popup.min.js')}}"></script> --}}
        <!-- SmoothScroll -->
        <script src="{{asset('frontAssets/js/SmoothScroll.js')}}"></script>
        <!-- Parallax js -->
        <script src="{{asset('frontAssets/js/parallaxie.js')}}"></script>
        <!-- MagicCursor js file -->
        <script src="{{asset('frontAssets/js/gsap.min.js')}}"></script>
        <script src="{{asset('frontAssets/js/magiccursor.js')}}"></script>
        <!-- Text Effect js file -->
        <script src="{{ asset('frontAssets/js/SplitText.js') }}"></script>
        <script src="{{asset('frontAssets/js/ScrollTrigger.min.js')}}"></script>
        <!-- YTPlayer js File -->
        <script src="{{asset('frontAssets/js/jquery.mb.YTPlayer.min.js')}}"></script>
        <!-- Wow js file -->
        <script src="{{asset('frontAssets/js/wow.min.js')}}"></script>
        <!-- Main Custom js file -->
        <script src="{{asset('frontAssets/js/function.js')}}"></script>
        {{-- <script src="../../demo.awaikenthemes.com/assets/js/theme-panel-dynamic.js"></script> --}}
    </body>

</html>

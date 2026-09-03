@extends('store.layouts.app')

@section('title', 'Page Under Construction')

@section('content')

<!-- error section start -->
<div class="error-page bg-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="error-page-image wow fadeInUp">
                    <img src="{{ asset('frontAssets/images/mosh-404/404.png') }}" alt="">
                </div>
                <div class="error-page-content">
                    <div class="section-title">
                        <h2 class="text-anime-style-2" data-cursor="-opaque">Oops! page not <span>found</span></h2>
                    </div>
                    <div class="error-page-content-body">
                        <p class="wow fadeInUp" data-wow-delay="0.2s">The page you are looking for does not exist.</p>
                        <a class="btn-default wow fadeInUp" data-wow-delay="0.4s" href="{{ url('/') }}">Back to Homepage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- error section end --> 
@endsection
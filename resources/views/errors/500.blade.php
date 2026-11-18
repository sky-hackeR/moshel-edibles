@extends('store.layouts.app')

@section('title', 'Server Error (500)')

@section('content')

<!-- error section start -->
<div class="error-page bg-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="error-page-image wow fadeInUp">
                    <img src="{{ asset('frontAssets/images/mosh-404/404.png') }}" alt="500 Internal Server Error">
                </div>
                <div class="error-page-content">
                    <div class="section-title">
                        <h2 class="text-anime-style-2" data-cursor="-opaque">Internal <span>Server Error</span></h2>
                    </div>
                    <div class="error-page-content-body">
                        <p class="wow fadeInUp" data-wow-delay="0.2s">Something unexpected interrupted our kitchen servers. We've been notified and are looking into it right away.</p>
                        <div class="d-flex flex-wrap align-items-center justify-content-center gap-3 mt-3">
                            <button type="button" onclick="window.location.reload();" class="btn-default wow fadeInUp" data-wow-delay="0.3s">
                                Retry Page
                            </button>
                            <a class="btn-default wow fadeInUp" data-wow-delay="0.4s" href="{{ url('/') }}">
                                Back to Homepage
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- error section end --> 
@endsection

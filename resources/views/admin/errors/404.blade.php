@extends('admin.layout.error')

@section('title', '404 - Page Not Found')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9 col-lg-7 col-xl-6">
        <div class="admin-error-card">
            {{-- ERROR ILLUSTRATION --}}
            <div class="mb-4">
                <img src="{{ asset('assets/images/error-img.png') }}" alt="404 Not Found" class="img-fluid mx-auto d-block" style="max-height: 180px;">
            </div>

            <div class="admin-error-code error-404 mb-2">404</div>
            <h4 class="text-dark fw-bold mb-2">Administrative Resource Not Found</h4>
            <p class="text-muted font-size-14 mb-4 mx-auto" style="max-width: 440px;">
                The administrative endpoint or page you are trying to reach (<code>{{ request()->getRequestUri() }}</code>) does not exist, has been archived, or the link is incorrect.
            </p>

            {{-- ACTIONS --}}
            <div class="d-flex flex-wrap align-items-center justify-content-center gap-2 pt-2">
                @if(auth('admin')->check())
                    <a href="{{ url('/admin/home') }}" class="btn btn-admin-primary">
                        <i class="bx bx-home-circle me-1"></i> Admin Dashboard
                    </a>
                    <a href="{{ url('/admin/inventory') }}" class="btn btn-outline-primary">
                        <i class="bx bx-package me-1"></i> Inventory Center
                    </a>
                @else
                    <a href="{{ url('/admin/login') }}" class="btn btn-admin-primary">
                        <i class="bx bx-log-in me-1"></i> Admin Sign In
                    </a>
                @endif

                <button type="button" onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ auth('admin')->check() ? url('/admin/home') : url('/admin/login') }}';" class="btn btn-light">
                    <i class="bx bx-arrow-back me-1"></i> Go Back
                </button>
            </div>

            {{-- QUICK SHORTCUT LINKS --}}
            <div class="pt-4 mt-4 border-top text-muted font-size-12 d-flex flex-wrap align-items-center justify-content-center gap-3">
                <a href="{{ url('/') }}" class="text-muted text-decoration-none">
                    <i class="bx bx-store me-1"></i> Moshel Storefront
                </a>
                <span>·</span>
                @if(auth('admin')->check())
                    <a href="{{ url('/admin/products') }}" class="text-muted text-decoration-none">
                        <i class="bx bx-box me-1"></i> Catalog Products
                    </a>
                    <span>·</span>
                    <a href="{{ url('/admin/pos') }}" class="text-muted text-decoration-none">
                        <i class="bx bx-cart me-1"></i> POS Register
                    </a>
                @else
                    <span class="text-muted">Requires authenticated administrator privileges.</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

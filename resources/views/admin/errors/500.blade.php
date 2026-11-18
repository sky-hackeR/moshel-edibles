@extends('admin.layout.error')

@section('title', '500 - Internal Server Error')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9 col-lg-7 col-xl-6">
        <div class="admin-error-card">
            {{-- DISRUPTION ICON BADGE --}}
            <div class="mb-3">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-danger" style="width: 84px; height: 84px; background: rgba(239, 68, 68, 0.1);">
                    <i class="bx bx-error-circle" style="font-size: 44px;"></i>
                </div>
            </div>

            <div class="admin-error-code error-500 mb-2">500</div>
            <h4 class="text-dark fw-bold mb-2">Internal System Disruption</h4>
            <p class="text-muted font-size-14 mb-4 mx-auto" style="max-width: 440px;">
                An unexpected system exception interrupted this administrative operation. Diagnostic details have been logged automatically.
            </p>

            {{-- SMART ACTIONS --}}
            <div class="d-flex flex-wrap align-items-center justify-content-center gap-2 pt-2">
                <button type="button" onclick="window.location.reload();" class="btn btn-admin-danger">
                    <i class="bx bx-refresh me-1"></i> Retry Operation
                </button>

                @if(auth('admin')->check())
                    <a href="{{ url('/admin/home') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-home-circle me-1"></i> Admin Dashboard
                    </a>
                    <a href="{{ url('/admin/inventory') }}" class="btn btn-outline-primary">
                        <i class="bx bx-package me-1"></i> Inventory Center
                    </a>
                @else
                    <a href="{{ url('/admin/login') }}" class="btn btn-outline-primary">
                        <i class="bx bx-log-in me-1"></i> Admin Sign In
                    </a>
                @endif
            </div>

            {{-- DIAGNOSTIC / SUPPORT HINT --}}
            <div class="pt-4 mt-4 border-top text-muted font-size-12">
                <p class="mb-1 text-muted">
                    <i class="bx bx-info-circle me-1 text-danger"></i> If this disruption is persistent across database transactions, inspect the system audit logs or contact engineering support.
                </p>
                <div class="d-flex align-items-center justify-content-center gap-2 mt-2">
                    <a href="{{ url('/') }}" class="text-muted text-decoration-none">
                        <i class="bx bx-store me-1"></i> Return to Storefront
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

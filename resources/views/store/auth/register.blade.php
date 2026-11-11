@extends('store.layouts.app')

@section('title', 'Create an Account')

@section('content')
<div class="page-header bg-section">
    <div class="container">
        <div class="page-header-box">
            <h1>Create Account</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('store.welcome') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Register</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section class="store-commerce-section bg-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-9">
                <div class="store-form-panel">
                    <div class="section-title text-center mb-4">
                        <span class="store-summary-kicker">Join Our Table</span>
                        <h2 style="font-size: 26px;">Create your Moshel account</h2>
                        <p class="text-muted" style="font-size: 14px;">Save your delivery addresses and track all fresh orders with ease.</p>
                    </div>

                    @if(isset($errors) && $errors->any())
                        <div class="alert alert-danger p-3 rounded-3 mb-4" role="alert" style="background: #ffebee; border: 1px solid #ffcdd2;">
                            <strong class="d-block mb-1 text-danger" style="font-size: 14px;"><i class="fa-solid fa-circle-exclamation me-1"></i> Registration issue:</strong>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li class="text-danger" style="font-size: 13px;">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('customer.register.submit') }}">
                        @csrf

                        <div class="store-form-field mb-3">
                            <label for="name">Full Name <span class="text-danger">*</span></label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g. Olugbenga David">
                            @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="store-form-field mb-3">
                            <label for="email">Email Address <span class="text-danger">*</span></label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="e.g. you@example.com">
                            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="store-form-field mb-0">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input id="password" type="password" name="password" required placeholder="Minimum 6 characters">
                                    @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="store-form-field mb-0">
                                    <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                                    <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Re-type password">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn-default btn-highlighted w-100 py-3 d-inline-flex align-items-center justify-content-center gap-2" style="font-size: 15px; font-weight: 600;">
                                <i class="fa-solid fa-user-plus"></i>
                                <span>Create Account</span>
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="mb-0 text-muted" style="font-size: 14px;">
                            Already have an account? 
                            <a href="{{ route('customer.login') }}" class="store-text-link fw-bold ms-1">Sign in here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

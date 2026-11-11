@extends('store.layouts.app')

@section('title', 'Customer Sign In')

@section('content')
<div class="page-header bg-section">
    <div class="container">
        <div class="page-header-box">
            <h1>Customer Sign In</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('store.welcome') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sign In</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section class="store-commerce-section bg-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <div class="store-form-panel">
                    <div class="section-title text-center mb-4">
                        <span class="store-summary-kicker">Welcome Back</span>
                        <h2 style="font-size: 26px;">Sign in to Moshel</h2>
                        <p class="text-muted" style="font-size: 14px;">Access your saved addresses, track fresh orders, and checkout seamlessly.</p>
                    </div>

                    @if(session('status'))
                        <div class="alert alert-success d-flex align-items-center p-3 rounded-3 mb-4" role="alert" style="background: #e8f5e9; border: 1px solid #c8e6c9;">
                            <i class="fa-solid fa-circle-check text-success fs-5 me-2"></i>
                            <span class="text-success fw-bold" style="font-size: 14px;">{{ session('status') }}</span>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info d-flex align-items-center p-3 rounded-3 mb-4" role="alert" style="background: #e1f5fe; border: 1px solid #b3e5fc;">
                            <i class="fa-solid fa-circle-info text-info fs-5 me-2"></i>
                            <span class="text-dark" style="font-size: 14px;">{{ session('info') }}</span>
                        </div>
                    @endif

                    @if(isset($errors) && $errors->any())
                        <div class="alert alert-danger p-3 rounded-3 mb-4" role="alert" style="background: #ffebee; border: 1px solid #ffcdd2;">
                            <strong class="d-block mb-1 text-danger" style="font-size: 14px;"><i class="fa-solid fa-circle-exclamation me-1"></i> Sign in failed:</strong>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li class="text-danger" style="font-size: 13px;">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('customer.login.submit') }}">
                        @csrf

                        <div class="store-form-field mb-3">
                            <label for="email">Email Address <span class="text-danger">*</span></label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="e.g. you@example.com">
                            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="store-form-field mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="mb-0">Password <span class="text-danger">*</span></label>
                                <a href="{{ route('customer.password.request') }}" class="store-text-link" style="font-size: 12px;">Forgot password?</a>
                            </div>
                            <input id="password" type="password" name="password" required placeholder="Enter your password">
                            @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <label class="store-check">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember me
                            </label>
                        </div>

                        <button type="submit" class="btn-default btn-highlighted w-100 py-3 d-inline-flex align-items-center justify-content-center gap-2" style="font-size: 15px; font-weight: 600;">
                            <i class="fa-solid fa-arrow-right-to-bracket"></i>
                            <span>Sign In</span>
                        </button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="mb-0 text-muted" style="font-size: 14px;">
                            New to Moshel Edibles? 
                            <a href="{{ route('customer.register') }}" class="store-text-link fw-bold ms-1">Create an account</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

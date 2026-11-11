@extends('store.layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="page-header bg-section">
    <div class="container">
        <div class="page-header-box">
            <h1>Profile</h1>
            {{-- <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('store.welcome') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customer.account') }}">Account</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profile Settings</li>
                </ol>
            </nav> --}}
        </div>
    </div>
</div>

<section class="store-commerce-section bg-section">
    <div class="container">
        <!-- Account User Header -->
        <div class="store-account-heading">
            <div>
                <span class="store-summary-kicker">Manage Details</span>
                <h2>{{ $customer->name }}</h2>
                <p><i class="fa-regular fa-envelope me-1"></i> {{ $customer->email }} @if($customer->phone) · <i class="fa-solid fa-phone ms-2 me-1"></i> {{ $customer->phone }} @endif</p>
            </div>
            <form method="POST" action="{{ route('customer.logout') }}">
                @csrf
                <button type="submit" class="store-logout-link">
                    <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Sign out
                </button>
            </form>
        </div>

        <!-- Navigation Tabs -->
        <div class="store-account-nav">
            <a href="{{ route('customer.account') }}"><i class="fa-solid fa-receipt"></i> Orders & History</a>
            <a class="active" href="{{ route('customer.account.profile') }}"><i class="fa-regular fa-user"></i> Profile Details</a>
            <a href="{{ route('customer.account.addresses') }}"><i class="fa-solid fa-location-dot"></i> Saved Addresses</a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="store-form-panel">
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center p-3 rounded-3 mb-4" role="alert" style="background: #e8f5e9; border: 1px solid #c8e6c9;">
                            <i class="fa-solid fa-circle-check fs-5 text-success me-3"></i>
                            <span class="text-success fw-bold">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(isset($errors) && $errors->any())
                        <div class="alert alert-danger p-3 rounded-3 mb-4" role="alert" style="background: #ffebee; border: 1px solid #ffcdd2;">
                            <strong class="d-block mb-1 text-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> Please check the following:</strong>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li class="text-danger" style="font-size: 13px;">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('customer.account.profile.update') }}">
                        @csrf 
                        @method('PUT')

                        <!-- Section 1: Contact Details -->
                        <div class="section-title mb-3">
                            <h3 style="font-size: 20px;"><i class="fa-regular fa-id-card me-2 text-muted"></i> Personal Information</h3>
                            <p class="text-muted" style="font-size: 13px;">Your primary contact identity used across Moshel Edibles orders.</p>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="store-form-field mb-0">
                                    <label for="profile_name">Full Name <span class="text-danger">*</span></label>
                                    <input id="profile_name" name="name" value="{{ old('name', $customer->name) }}" required placeholder="e.g. Olugbenga David">
                                    @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="store-form-field mb-0">
                                    <label for="profile_email">Email Address <span class="text-danger">*</span></label>
                                    <input id="profile_email" type="email" name="email" value="{{ old('email', $customer->email) }}" required placeholder="e.g. you@example.com">
                                    @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                        </div>

                        <hr class="store-form-divider my-4">

                        <!-- Section 2: Delivery Defaults -->
                        <div class="section-title mb-3">
                            <h3 style="font-size: 20px;"><i class="fa-solid fa-truck-fast me-2 text-muted"></i> Default Delivery Details</h3>
                            <p class="text-muted" style="font-size: 13px;">Pre-fills automatically during checkout so you can order in seconds.</p>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <div class="store-form-field mb-3">
                                    <label for="profile_phone">Delivery Phone (WhatsApp Preferred)</label>
                                    <input id="profile_phone" type="tel" name="phone" value="{{ old('phone', $customer->phone) }}" placeholder="e.g. +234 808 000 0000">
                                    @error('phone')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="store-form-field mb-0">
                                    <label for="profile_address">Default Delivery Address</label>
                                    <textarea id="profile_address" name="address" rows="3" placeholder="House/Flat number, Street name, Estate / Landmark, City and State...">{{ old('address', $customer->address) }}</textarea>
                                    @error('address')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                        </div>

                        <hr class="store-form-divider my-4">

                        <!-- Section 3: Password Update -->
                        <div class="section-title mb-3">
                            <h3 style="font-size: 20px;"><i class="fa-solid fa-shield-halved me-2 text-muted"></i> Security & Password</h3>
                            <p class="text-muted" style="font-size: 13px;">Leave all password fields blank if you do not want to change your password.</p>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <div class="store-form-field mb-3">
                                    <label for="profile_current_password">Current Password</label>
                                    <input id="profile_current_password" type="password" name="current_password" placeholder="Required only if changing password">
                                    @error('current_password')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="store-form-field mb-0">
                                    <label for="profile_password">New Password</label>
                                    <input id="profile_password" type="password" name="password" placeholder="Minimum 6 characters">
                                    @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="store-form-field mb-0">
                                    <label for="profile_password_confirmation">Confirm New Password</label>
                                    <input id="profile_password_confirmation" type="password" name="password_confirmation" placeholder="Re-type new password">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end pt-3">
                            <button class="btn-default btn-highlighted py-3 px-4 d-inline-flex align-items-center gap-2" type="submit" style="font-size: 15px; font-weight: 600;">
                                <i class="fa-solid fa-floppy-disk"></i>
                                <span>Save Profile Changes</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@extends('admin.layout.auth')

<!-- Main Content -->
@section('content')

<div class="bg-primary-subtle border-bottom border-light">
    <div class="row">
        <div class="col-7">
            <div class="text-primary p-4">
                <h5 class="text-primary fw-bold mb-1">Reset Password</h5>
                <p class="font-size-13 opacity-75">Restore access to {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : 'Admin' }}</p>
            </div>
        </div>
        <div class="col-5 align-self-end">
            <img src="{{ asset('assets/images/profile-img.png') }}" alt="" class="img-fluid" style="max-height: 100px;">
        </div>
    </div>
</div>

<div class="card-body pt-0"> 
    <div class="auth-logo text-center">
        <div class="avatar-md profile-user-wid mb-4 mx-auto" style="margin-top: -30px;">
            <span class="avatar-title rounded-circle bg-white shadow-lg p-2">
                @if(!empty($pageGlobalData->setting->favicon))
                    <img src="{{ asset($pageGlobalData->setting->favicon) }}" alt="Logo" class="rounded-circle" height="40">
                @else
                    <i class="mdi mdi-lock-reset text-primary" style="font-size: 40px;"></i>
                @endif
            </span>
        </div>
    </div>
    
    <div class="p-2">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show text-center mb-4 shadow-sm border-0" role="alert">
                <i class="mdi mdi-check-all me-2"></i>
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="alert alert-info text-center mb-4 border-0 bg-info-subtle text-info font-size-13" role="alert">
            Enter your Email and instructions will be sent to you!
        </div>

        <form class="form-horizontal" method="POST" action="{{ url('/admin/password/email') }}">
            @csrf
            
            <div class="mb-4">
                <label for="email" class="form-label font-size-13 fw-semibold">Email Address</label>
                <div class="input-group bg-light rounded-3 p-1">
                    <span class="input-group-text bg-transparent border-0">
                        <i class="mdi mdi-email-send-outline text-primary font-size-18"></i>
                    </span>
                    <input type="email" name="email" class="form-control border-0 bg-transparent" id="email" 
                           placeholder="Enter registered email" value="{{ old('email') }}" required autofocus>
                </div>
                @error('email')
                    <span class="text-danger font-size-12 mt-1 d-block"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="mt-3 d-grid">
                <button class="btn btn-primary rounded-pill p-2 fw-bold shadow-primary" type="submit">
                    SEND RESET LINK <i class="mdi mdi-send ms-2 font-size-16"></i>
                </button>
            </div>

            <div class="mt-4 text-center">
                <p class="mb-0 font-size-13 text-muted">Wait, I remember my password... </p>
                <a href="{{ url('/admin/login') }}" class="fw-bold text-primary text-decoration-underline">Back to Login</a>
            </div>

        </form>
    </div>
</div>

@endsection
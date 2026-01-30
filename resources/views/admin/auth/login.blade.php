@extends('admin.layout.auth')
@section('content')
    <div class="bg-primary-subtle border-bottom border-light">
        <div class="row">
            <div class="col-7">
                <div class="text-primary p-4">
                    <h5 class="text-primary fw-bold mb-1">Admin Control</h5>
                    <p class="font-size-13 opacity-75">Secure access to {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : 'Portal' }}</p>
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
                        <i class="mdi mdi-account-circle text-primary" style="font-size: 40px;"></i>
                    @endif
                </span>
            </div>
        </div>

        <div class="p-2">
            <form class="form-horizontal" method="POST" action="{{ url('/admin/login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label font-size-13 fw-semibold">Admin Identifier</label>
                    <div class="input-group bg-light rounded-3 p-1">
                        <span class="input-group-text bg-transparent border-0">
                            <i class="mdi mdi-account-key-outline text-primary font-size-18"></i>
                        </span>
                        <input type="email" name="email" class="form-control border-0 bg-transparent" id="email" 
                            placeholder="admin@domain.com" value="{{ old('email') }}" required autofocus>
                    </div>
                    @error('email')
                        <span class="text-danger font-size-12 mt-1 d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label font-size-13 fw-semibold">Security Password</label>
                    <div class="input-group auth-pass-inputgroup bg-light rounded-3 p-1">
                        <span class="input-group-text bg-transparent border-0">
                            <i class="mdi mdi-shield-lock-outline text-primary font-size-18"></i>
                        </span>
                        <input type="password" name="password" class="form-control border-0 bg-transparent" 
                            placeholder="••••••••" aria-label="Password" aria-describedby="password-addon" required>
                        <button class="btn btn-light bg-transparent border-0" type="button" id="password-addon">
                            <i class="mdi mdi-eye-outline"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-danger font-size-12 mt-1 d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="d-flex align-items-center justify-content-between mt-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember-check" name="remember">
                        <label class="form-check-label text-muted font-size-13" for="remember-check">
                            Keep me logged in
                        </label>
                    </div>
                    <a href="{{ url('/admin/password/reset') }}" class="text-primary font-size-13">Recovery?</a>
                </div>
                
                <div class="mt-4 d-grid">
                    <button class="btn btn-primary rounded-pill p-2 fw-bold shadow-primary" type="submit">
                        AUTHENTICATE <i class="mdi mdi-login-variant ms-2 font-size-16"></i>
                    </button>
                </div>

                <div class="mt-4 text-center">
                    <p class="text-muted font-size-12 mb-0">--- Authorized Personnel Only ---</p>
                </div>
            </form>
        </div>
    </div>
@endsection
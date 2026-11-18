@extends('admin.layout.auth')

@section('title', 'Admin Login')

@section('content')
    {{-- TOP BANNER (Follows original Skote profile-img design) --}}
    <div class="admin-auth-banner">
        <div class="row g-0 align-items-center">
            <div class="col-7">
                <div class="p-4">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill font-size-11 px-2.5 py-0.5 mb-2 d-inline-block">
                        <i class="mdi mdi-shield-crown me-1"></i> Executive Portal
                    </span>
                    <h5 class="text-primary fw-bold mb-1">Admin Control</h5>
                    <p class="font-size-12 text-muted mb-0">
                        Secure access to {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : 'Moshel' }}
                    </p>
                </div>
            </div>
            <div class="col-5 align-self-end text-end pe-3">
                <img src="{{ asset('assets/images/profile-img.png') }}" alt="Admin Portal" class="img-fluid" style="max-height: 95px;">
            </div>
        </div>
    </div>

    {{-- CARD BODY & FLOATING AVATAR --}}
    <div class="card-body pt-0 px-4 pb-4"> 
        <div class="text-center">
            <div class="admin-auth-avatar">
                @if(!empty($pageGlobalData->setting?->favicon))
                    <img src="{{ asset($pageGlobalData->setting->favicon) }}" alt="Logo" class="rounded-circle" height="38" width="38" style="object-fit: contain;">
                @else
                    <i class="mdi mdi-account-key-outline text-primary font-size-28"></i>
                @endif
            </div>
        </div>

        <div class="pt-3">
            <form class="form-horizontal" method="POST" action="{{ url('/admin/login') }}" id="adminLoginForm">
                @csrf

                {{-- EMAIL / ADMIN IDENTIFIER --}}
                <div class="mb-3">
                    <label for="email" class="form-label font-size-13 fw-semibold text-dark mb-1">
                        Admin Identifier <span class="text-danger">*</span>
                    </label>
                    <div class="input-group admin-auth-input-group @error('email') border-danger @enderror">
                        <span class="input-group-text">
                            <i class="mdi mdi-account-key-outline text-primary font-size-18"></i>
                        </span>
                        <input type="email" name="email" class="form-control" id="email" 
                               placeholder="admin@domain.com" value="{{ old('email') }}" required autofocus autocomplete="username">
                    </div>
                    @error('email')
                        <span class="text-danger font-size-12 mt-1 d-block">
                            <i class="mdi mdi-alert-circle-outline me-1"></i><strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- SECURITY PASSWORD --}}
                <div class="mb-3">
                    <label for="admin_password" class="form-label font-size-13 fw-semibold text-dark mb-1">
                        Security Password <span class="text-danger">*</span>
                    </label>
                    <div class="input-group admin-auth-input-group @error('password') border-danger @enderror">
                        <span class="input-group-text">
                            <i class="mdi mdi-shield-lock-outline text-primary font-size-18"></i>
                        </span>
                        <input type="password" name="password" class="form-control" id="admin_password" 
                               placeholder="••••••••" required autocomplete="current-password">
                        <button class="btn btn-light bg-transparent border-0 text-muted" type="button" id="togglePasswordBtn" title="Toggle password visibility">
                            <i class="mdi mdi-eye-outline" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-danger font-size-12 mt-1 d-block">
                            <i class="mdi mdi-alert-circle-outline me-1"></i><strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- REMEMBER ME & RECOVERY --}}
                <div class="d-flex align-items-center justify-content-between mt-3 mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember-check" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-muted font-size-13" for="remember-check" style="cursor: pointer;">
                            Keep me logged in
                        </label>
                    </div>
                    <a href="{{ url('/admin/password/reset') }}" class="text-primary font-size-13 fw-medium text-decoration-none">
                        Recovery?
                    </a>
                </div>
                
                {{-- SUBMIT BUTTON --}}
                <div class="d-grid">
                    <button class="btn btn-admin-primary fw-bold" type="submit" id="adminLoginBtn">
                        <span id="btnText">AUTHENTICATE <i class="mdi mdi-login-variant ms-1 font-size-16 align-middle"></i></span>
                        <span id="btnSpinner" class="d-none">
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            Verifying...
                        </span>
                    </button>
                </div>

                {{-- SECURITY DISCLAIMER --}}
                <div class="mt-4 text-center">
                    <p class="text-muted font-size-11 mb-0 letter-spacing-1 text-uppercase">
                        <i class="mdi mdi-shield-check-outline text-success me-1"></i> Authorized Personnel Only · 256-Bit Encrypted
                    </p>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Password visibility toggle
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('admin_password');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passwordInput && toggleIcon) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                toggleIcon.classList.toggle('mdi-eye-outline', !isPassword);
                toggleIcon.classList.toggle('mdi-eye-off-outline', isPassword);
            });
        }

        // Submit button loading state
        const form = document.getElementById('adminLoginForm');
        const submitBtn = document.getElementById('adminLoginBtn');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');

        if (form && submitBtn) {
            form.addEventListener('submit', function () {
                submitBtn.disabled = true;
                btnText.classList.add('d-none');
                btnSpinner.classList.remove('d-none');
            });
        }
    });
</script>
@endpush

@extends('staff.layout.auth')

@section('content')

<div class="mb-3">
    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill font-size-11 mb-2 d-inline-flex align-items-center gap-1">
        <span style="width: 6px; height: 6px; border-radius: 50%; background: #059669;"></span> Staff Terminal
    </span>
    <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.02em;">Staff Sign In</h3>
    <p class="text-muted font-size-13 mb-4">Enter your authorized email and password to access the POS register and bakery operations.</p>
</div>

<form id="loginForm" action="{{ url('/staff/login') }}" method="POST">
    @csrf

    {{-- EMAIL ADDRESS --}}
    <div class="mb-3">
        <label for="email" class="form-label font-size-13 fw-semibold text-dark mb-1.5">Staff Email Address</label>
        <div class="input-group staff-input-group">
            <span class="input-group-text"><i class="bx bx-envelope font-size-18"></i></span>
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control font-size-13 @error('email') is-invalid @enderror"
                   placeholder="e.g. staff@moshel.test"
                   value="{{ old('email') }}"
                   required
                   autofocus>
        </div>
        @error('email')
            <span class="text-danger font-size-12 mt-1 d-block"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    {{-- PASSWORD --}}
    <div class="mb-3">
        <div class="d-flex align-items-center justify-content-between mb-1.5">
            <label for="password-input" class="form-label font-size-13 fw-semibold text-dark mb-0">Password</label>
            <a href="{{ url('/staff/password/reset') }}" class="font-size-12 text-primary text-decoration-none fw-medium">
                Forgot password?
            </a>
        </div>
        <div class="input-group staff-input-group">
            <span class="input-group-text"><i class="bx bx-lock-alt font-size-18"></i></span>
            <input type="password"
                   name="password"
                   id="password-input"
                   class="form-control font-size-13 @error('password') is-invalid @enderror"
                   placeholder="Enter your terminal password"
                   required>
            <button class="btn btn-link text-muted text-decoration-none px-3" type="button" id="password-addon" aria-label="Toggle password visibility">
                <i class="mdi mdi-eye-outline font-size-18" id="password-icon"></i>
            </button>
        </div>
        @error('password')
            <span class="text-danger font-size-12 mt-1 d-block"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    {{-- REMEMBER ME --}}
    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" name="remember" id="remember-check" {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label text-muted font-size-13" for="remember-check">
            Keep my workstation signed in
        </label>
    </div>

    {{-- SUBMIT BUTTON --}}
    <div class="d-grid mb-4">
        <button id="submitBtn" class="btn btn-staff-primary w-100 py-2.5 font-size-14 d-flex align-items-center justify-content-center gap-2" type="submit">
            <span class="btn-text fw-semibold">Sign In to Workstation</span>
            <i class="bx bx-right-arrow-alt font-size-18 btn-text"></i>
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
        </button>
    </div>
</form>

{{-- REASSURANCE CARD --}}
<div class="p-3 rounded-3 bg-light border text-center font-size-12 text-muted">
    <i class="bx bx-info-circle text-primary me-1"></i>
    <span>New staff member or locked out? Contact your <strong>Store Administrator</strong> to setup or reset your terminal credentials.</span>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Password Visibility Toggle
        $('#password-addon').on('click', function() {
            const input = $('#password-input');
            const icon = $('#password-icon');
            
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('mdi-eye-outline').addClass('mdi-eye-off-outline');
            } else {
                input.attr('type', 'password');
                icon.removeClass('mdi-eye-off-outline').addClass('mdi-eye-outline');
            }
        });

        // Loading State on Submit
        $('#loginForm').on('submit', function() {
            const btn = $('#submitBtn');
            const text = btn.find('.btn-text');
            const spinner = btn.find('.spinner-border');

            btn.prop('disabled', true);
            text.addClass('d-none');
            spinner.removeClass('d-none');
        });
    });
</script>
@endpush

@endsection

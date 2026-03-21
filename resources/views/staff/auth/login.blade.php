@extends('staff.layout.auth')

@section('content')

<div class="mb-4 mb-md-5">
    <a href="{{ url('/') }}" class="d-block card-logo">
        <img src="{{ !empty($pageGlobalData->setting->favicon) ? asset($pageGlobalData->setting->favicon) : asset('assets/images/logo-dark.png') }}" alt="Logo" height="30" class="card-logo-dark">
    </a>
</div>

<div class="my-auto">
    <div>
        <h5 class="text-primary fw-bold">Staff Login</h5>
        <p class="text-muted">Enter your credentials to access the terminal.</p>
    </div>

    <div class="mt-4">
        <form id="loginForm" action="{{ url('/staff/login') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="email" class="form-label fw-medium">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="staff@example.com" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="mb-3">
                <div class="float-end">
                    <a href="{{ url('/staff/password/reset') }}" class="text-muted small">Forgot password?</a>
                </div>
                <label class="form-label fw-medium">Password</label>
                <div class="input-group auth-pass-inputgroup">
                    <input type="password" name="password" id="password-input" class="form-control" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon" required>
                    <button class="btn btn-light" type="button" id="password-addon">
                        <i class="mdi mdi-eye-outline" id="password-icon"></i>
                    </button>
                </div>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember-check">
                <label class="form-check-label text-muted" for="remember-check">
                    Keep me signed in
                </label>
            </div>

            <div class="mt-4 d-grid">
                <button id="submitBtn" class="btn btn-primary waves-effect waves-light fw-bold" type="submit">
                    <span class="btn-text">LOG IN</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // 1. Password Visibility Toggle
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

        // 2. Loading State on Submit
        $('#loginForm').on('submit', function() {
            const btn = $('#submitBtn');
            const text = btn.find('.btn-text');
            const spinner = btn.find('.spinner-border');

            // Disable button and show spinner
            btn.prop('disabled', true);
            text.addClass('d-none');
            spinner.removeClass('d-none');
        });
    });
</script>
@endsection
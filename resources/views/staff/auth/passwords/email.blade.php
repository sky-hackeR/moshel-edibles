@extends('staff.layout.auth')

@section('content')
<div class="mb-4 mb-md-5">
    <a href="{{ url('/') }}" class="d-block card-logo">
        <img src="{{ !empty($pageGlobalData->setting->favicon) ? asset($pageGlobalData->setting->favicon) : asset('assets/images/logo-dark.png') }}" alt="Logo" height="30" class="card-logo-dark">
    </a>
</div>

<div class="my-auto">
    <div>
        <h5 class="text-primary fw-bold">Reset Password</h5>
        <p class="text-muted">Enter your email to receive a password reset link.</p>
    </div>

    <div class="mt-4">
        @if (session('status'))
            <div class="alert alert-success border-0 bg-success-subtle text-success fw-medium" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form id="resetForm" method="POST" action="{{ url('/staff/password/email') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label fw-medium">Email Address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" placeholder="Enter your registered email" required autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="d-grid">
                <button id="submitBtn" class="btn btn-primary fw-bold p-2" type="submit">
                    <span class="btn-text">SEND RESET LINK</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>

            <div class="mt-4 text-center">
                <p class="mb-0">Remember it? <a href="{{ url('/staff/login') }}" class="fw-medium text-primary"> Login </a></p>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // Loading State on Submit
        $('#resetForm').on('submit', function() {
            const btn = $('#submitBtn');
            const text = btn.find('.btn-text');
            const spinner = btn.find('.spinner-border');

            btn.prop('disabled', true);
            text.addClass('d-none');
            spinner.removeClass('d-none');
        });
    });
</script>
@endsection

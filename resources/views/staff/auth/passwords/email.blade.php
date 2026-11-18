@extends('staff.layout.auth')

@section('content')

<div class="mb-3">
    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 rounded-pill font-size-11 mb-2 d-inline-flex align-items-center gap-1">
        <i class="bx bx-key"></i> Password Recovery
    </span>
    <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.02em;">Reset Staff Password</h3>
    <p class="text-muted font-size-13 mb-4">Enter your registered staff email address to receive a secure workstation recovery link.</p>
</div>

@if (session('status'))
    <div class="alert alert-success border-0 bg-success-subtle text-success fw-medium font-size-13 d-flex align-items-center gap-2 p-3 rounded-3 mb-4 shadow-xs" role="alert">
        <i class="bx bx-check-circle font-size-20 flex-shrink-0"></i>
        <div>{{ session('status') }}</div>
    </div>
@endif

<form id="resetForm" method="POST" action="{{ url('/staff/password/email') }}">
    @csrf

    {{-- EMAIL ADDRESS --}}
    <div class="mb-4">
        <label for="email" class="form-label font-size-13 fw-semibold text-dark mb-1.5">Staff Email Address</label>
        <div class="input-group staff-input-group">
            <span class="input-group-text"><i class="bx bx-envelope font-size-18"></i></span>
            <input id="email"
                   type="email"
                   class="form-control font-size-13 @error('email') is-invalid @enderror" 
                   name="email"
                   value="{{ old('email') }}"
                   placeholder="Enter your registered staff email"
                   required
                   autofocus>
        </div>
        @error('email')
            <span class="text-danger font-size-12 mt-1 d-block"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    {{-- SUBMIT BUTTON --}}
    <div class="d-grid mb-4">
        <button id="submitBtn" class="btn btn-staff-primary w-100 py-2.5 font-size-14 d-flex align-items-center justify-content-center gap-2" type="submit">
            <span class="btn-text fw-semibold">Send Password Reset Link</span>
            <i class="bx bx-send font-size-18 btn-text"></i>
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
        </button>
    </div>

    {{-- BACK TO LOGIN --}}
    <div class="text-center pt-2 font-size-13">
        <span class="text-muted">Remember your credentials?</span>
        <a href="{{ url('/staff/login') }}" class="fw-semibold text-primary text-decoration-none ms-1">
            Sign In Here <i class="bx bx-right-arrow-alt"></i>
        </a>
    </div>
</form>

@push('scripts')
<script>
    $(document).ready(function() {
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
@endpush

@endsection

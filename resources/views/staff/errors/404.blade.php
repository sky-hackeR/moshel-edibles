@extends('staff.layout.dashboard')

@section('content')
<div class="row justify-content-center my-5">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-none bg-transparent text-center p-4">
            <div class="card-body">
                <div class="ex-page-content">
                    <h1 class="display-1 fw-bold text-primary mb-2">404</h1>
                    <h4 class="text-uppercase mb-3">Oops! Page Not Found</h4>
                    <p class="text-muted mb-4">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
                    
                    <div class="mt-4">
                        <a class="btn btn-primary waves-effect waves-light" href="{{ url('/staff/home') }}">
                            <i class="bx bx-home-circle me-1"></i> Return to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
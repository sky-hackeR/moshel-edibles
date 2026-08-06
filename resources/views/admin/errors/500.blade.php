@extends('admin.layout.dashboard')

@section('content')
<div class="row justify-content-center my-5">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-none bg-transparent text-center p-4">
            <div class="card-body">
                <div class="ex-page-content">
                    <h1 class="display-1 fw-bold text-danger mb-2">500</h1>
                    <h4 class="text-uppercase mb-3">Internal Server Error</h4>
                    <p class="text-muted mb-4">Something went wrong on our end. Our technical systems have logged the issue and we are looking into it right away.</p>
                    
                    <div class="mt-4">
                        <a class="btn btn-danger waves-effect waves-light" href="{{ url('/admin/home') }}">
                            <i class="bx bx-home-circle me-1"></i> Return to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('admin.layout.dashboard')

@section('content')

@php
    $name = trim($admin->name);
    $parts = preg_split('/\s+/', $name, -1, PREG_SPLIT_NO_EMPTY);
    $count = count($parts);

    $surname = $parts[0] ?? null;
    $firstName = null;
    $middleName = null;

    if ($count === 2) {
        $firstName = $parts[1];
    } elseif ($count >= 3) {
        $firstName = $parts[1];
        $middleName = implode(' ', array_slice($parts, 2));
    }
@endphp


<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Admin Profile</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-4">
        <div class="card overflow-hidden">
            <div class="bg-primary-subtle p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="text-primary mb-0">Welcome {{ explode(' ', $admin->name)[0] }}!</h5>
                </div>
                <img src="{{ asset('assets/images/profile-img.png') }}" alt="" class="img-fluid rounded" style="max-width: 120px;">
            </div>
        
            <div class="card-body pt-0">
                <div class="d-flex align-items-start gap-3 mt-3">
                    <div class="flex-shrink-0">
                        <div class="avatar-md shadow-sm" style="width: 100px; height: 100px;">
                            <span class="avatar-title bg-primary text-white display-5" style="border-radius: 10px !important;">
                                {{ $admin->name ? strtoupper(substr($admin->name, 0, 1)) : 'A' }}
                            </span>
                        </div>
                    </div>
        
                    <div class="flex-grow-1">
                        <h5 class="font-size-15 text-truncate mb-1">{{ $admin->name }}</h5>
                        <p class="text-muted mb-0 text-truncate">Administrator</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Account Summary</h4>
                <div class="table-responsive">
                    <table class="table table-nowrap mb-0">
                        <tbody>
                            @if($surname)
                                <tr>
                                    <th scope="row">Surname :</th>
                                    <td>{{ $surname }}</td>
                                </tr>
                            @endif
                            @if($firstName)
                                <tr>
                                    <th scope="row">First Name :</th>
                                    <td>{{ $firstName }}</td>
                                </tr>
                            @endif
                            @if($middleName)
                                <tr>
                                    <th scope="row">Middle Name :</th>
                                    <td>{{ $middleName }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th scope="row">E-mail :</th>
                                <td>{{ $admin->email }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>         
    
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Profile & Security</h4>
                <hr>
    
                <div class="accordion" id="adminProfileAccordion">
    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingAccount">
                            <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAccount" aria-expanded="true">
                                Basic Information
                            </button>
                        </h2>
                        <div id="collapseAccount" class="accordion-collapse collapse show" data-bs-parent="#adminProfileAccordion">
                            <div class="accordion-body">
                                <form action="{{ url('/admin/updateProfile') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 mb-3">
                                            <label class="form-label">Full Name</label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name', $admin->name) }}">
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <label class="form-label">Email Address</label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email', $admin->email) }}">
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">Update Details</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSecurity">
                            <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSecurity">
                                Change Password
                            </button>
                        </h2>
                        <div id="collapseSecurity" class="accordion-collapse collapse" data-bs-parent="#adminProfileAccordion">
                            <div class="accordion-body">
                                <form action="{{ url('/admin/updatePassword') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Current Password</label>
                                        <input type="password" name="current_password" class="form-control">
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">New Password</label>
                                            <input type="password" name="new_password" class="form-control">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Confirm New Password</label>
                                            <input type="password" name="new_password_confirmation" class="form-control">
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-danger">Update Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
    
                </div> 
            </div>
        </div>
    </div>
</div>

@endsection
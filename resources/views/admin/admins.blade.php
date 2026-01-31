@extends('admin.layout.dashboard')

@section('content')

{{-- PAGE HEADER --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Users Management</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Admins</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-9">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="card-title mb-0">System Administrators</h4>
                    <p class="text-muted mb-0 small">Users with full access to the management dashboard.</p>
                </div>
                <button class="btn btn-primary btn-rounded px-4" data-bs-toggle="modal" data-bs-target="#addAdmin">
                    <i class="mdi mdi-plus-circle-outline me-1"></i> Add Admin
                </button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hover table-bordered align-middle dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">S/N</th>
                                <th>Administrator Name</th>
                                <th>Email Address</th>
                                <th>Account Created</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $admin)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-3">
                                            <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-12">
                                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h5 class="font-size-14 mb-0 text-dark">{{ $admin->name }}</h5>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->created_at->format('d M, Y') }}</td>
                                <td class="text-center">
                                    <button class="btn btn-soft-danger btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteAdmin{{ $admin->id }}"
                                            title="Remove Admin">
                                        <i class="mdi mdi-trash-can-outline font-size-16"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT SIDEBAR: QUICK STATS --}}
    <div class="col-xl-3">
        <div class="card shadow-sm border-0 bg-info text-white mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-white-50 fw-medium mb-2">Total Admins</p>
                        <h3 class="text-white mb-0">{{ $admins->count() }}</h3>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-soft-light text-white rounded-circle font-size-20">
                            <i class="mdi mdi-shield-account"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Security Note</h5>
                <div class="alert alert-soft-info border-info font-size-13 mb-0">
                    <i class="mdi mdi-information-outline me-1"></i> 
                    Admins have high-level permissions. Ensure passwords are kept secure and accounts are revoked if no longer needed.
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODALS SECTION --}}

@foreach($admins as $admin)
    {{-- DELETE MODAL --}}
    <div class="modal fade" id="deleteAdmin{{ $admin->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ url('admin/deleteAdmin') }}" class="w-100">
                @csrf
                <input type="hidden" name="admin_id" value="{{ $admin->id }}">
                <div class="modal-content border-0 shadow">
                    <div class="modal-body text-center p-5">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-soft-danger text-danger display-4 rounded-circle">
                                <i class="mdi mdi-alert-outline"></i>
                            </div>
                        </div>
                        <h4 class="text-danger">Revoke Access?</h4>
                        <p class="text-muted">You are about to remove <strong>{{ $admin->name }}</strong>. This action cannot be undone.</p>
                        
                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal">Go Back</button>
                            <button class="btn btn-danger w-50">Confirm Delete</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

{{-- ADD ADMIN MODAL --}}
<div class="modal fade" id="addAdmin" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ url('admin/newAdmin') }}" class="w-100">
            @csrf
            <div class="modal-content border-0">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Create New Admin Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter full name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Address *</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="admin@example.com" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Password *</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Confirm Password *</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Discard</button>
                    <button class="btn btn-primary px-4">Create Admin Account</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
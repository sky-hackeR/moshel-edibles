@extends('admin.layout.dashboard')

@section('content')

{{-- PAGE HEADER --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Users Management</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Staff</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- LEFT PANEL: STAFF TABLE --}}
    <div class="col-xl-9">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="card-title mb-0">System Staff</h4>
                    <p class="text-muted mb-0 small">Manage operational staff accounts and system access.</p>
                </div>
                <button class="btn btn-primary btn-rounded px-4" data-bs-toggle="modal" data-bs-target="#addStaff">
                    <i class="mdi mdi-account-plus-outline me-1"></i> Add Staff
                </button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hover table-bordered align-middle dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">S/N</th>
                                <th>Staff Name</th>
                                <th>Email Address</th>
                                <th>Onboarded Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staffs as $staff)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-3">
                                            <span class="avatar-title rounded-circle bg-soft-success text-success font-size-12">
                                                {{ strtoupper(substr($staff->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h5 class="font-size-14 mb-0 text-dark">{{ $staff->name }}</h5>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $staff->email }}</td>
                                <td>{{ $staff->created_at->format('d M, Y') }}</td>
                                <td class="text-center">
                                    <button class="btn btn-soft-info btn-sm me-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#viewStaff{{ $staff->id }}"
                                            title="View Profile">
                                        <i class="mdi mdi-eye-outline font-size-16"></i>
                                    </button>

                                    <button class="btn btn-soft-danger btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteStaff{{ $staff->id }}"
                                            title="Remove Staff">
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

    {{-- RIGHT PANEL: LIVE STATS --}}
    <div class="col-xl-3">
        <div class="card shadow-sm border-0 bg-success text-white mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-white-50 fw-medium mb-2">Active Staff</p>
                        <h3 class="text-white mb-0">{{ $staffs->count() }}</h3>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-soft-light text-white rounded-circle font-size-20">
                            <i class="mdi mdi-account-group"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-3">Staff Permissions</h5>
                <div class="alert alert-soft-success border-success font-size-13 mb-0">
                    <i class="mdi mdi-shield-check-outline me-1"></i> 
                    Staff accounts generally have restricted access compared to Admins. They can record production and manage inventory but cannot delete core system logs.
                </div>
                
                <ul class="list-group list-group-flush mt-3 font-size-13">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        Total Staff <span class="badge bg-success rounded-pill">{{ $staffs->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        New This Month <span class="badge bg-info rounded-pill">{{ $staffs->where('created_at', '>=', now()->startOfMonth())->count() }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- MODALS SECTION --}}

@foreach($staffs as $staff)
    {{-- DELETE STAFF MODAL --}}
    <div class="modal fade" id="deleteStaff{{ $staff->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ url('admin/deleteStaff') }}" class="w-100">
                @csrf
                <input type="hidden" name="staff_id" value="{{ $staff->id }}">
                <div class="modal-content border-0 shadow">
                    <div class="modal-body text-center p-5">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-soft-danger text-danger display-4 rounded-circle">
                                <i class="mdi mdi-account-remove-outline"></i>
                            </div>
                        </div>
                        <h4 class="text-danger">Remove Staff Member?</h4>
                        <p class="text-muted">You are about to revoke access for <strong>{{ $staff->name }}</strong>. This staff member will no longer be able to log in.</p>
                        
                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-danger w-50 shadow-sm">Confirm Deletion</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>



    {{-- VIEW STAFF MODAL --}}
    <div class="modal fade" id="viewStaff{{ $staff->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Staff Profile Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    @php
                        $name = trim($staff->name);
                        $parts = preg_split('/\s+/', $name, -1, PREG_SPLIT_NO_EMPTY);
                        $count = count($parts);

                        // Nigerian Convention: Surname is usually the first part
                        $surname = $parts[0] ?? null;
                        $firstName = null;
                        $middleName = null;

                        if ($count === 2) {
                            $firstName = $parts[1];
                        } elseif ($count >= 3) {
                            $firstName = $parts[1];
                            // Everything after the first two parts is treated as Middle Name(s)
                            $middleName = implode(' ', array_slice($parts, 2));
                        }
                    @endphp

                    <div class="card mb-0 border-0">
                        <div class="row g-0 align-items-stretch">
                            {{-- LEFT SIDE: AVATAR & ROLE --}}
                            <div class="col-md-4 border-end text-center p-4 bg-light-subtle d-flex flex-column justify-content-center">
                                <div class="avatar-xl mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-soft-success text-success display-4 shadow-sm">
                                        {{ $surname ? strtoupper(substr($surname, 0, 1)) : 'S' }}
                                    </span>
                                </div>
                                <h5 class="font-size-16 mb-1 text-dark">{{ $staff->name }}</h5>
                                <div>
                                    <span class="badge rounded-pill bg-soft-success text-success px-3">Operations Staff</span>
                                </div>
                            </div>

                            {{-- RIGHT SIDE: DETAILS --}}
                            <div class="col-md-8">
                                <div class="card-body p-4">
                                    <h6 class="text-muted text-uppercase font-size-11 fw-bold mb-3" style="letter-spacing: 0.5px;">
                                        Identity Breakdown
                                    </h6>
                                    
                                    @if($surname)
                                    <div class="row mb-3">
                                        <div class="col-sm-4 text-muted font-size-13">Surname:</div>
                                        <div class="col-sm-8 fw-semibold text-dark">{{ $surname }}</div>
                                    </div>
                                    @endif

                                    @if($firstName)
                                    <div class="row mb-3">
                                        <div class="col-sm-4 text-muted font-size-13">First Name:</div>
                                        <div class="col-sm-8 fw-semibold text-dark">{{ $firstName }}</div>
                                    </div>
                                    @endif

                                    @if($middleName)
                                    <div class="row mb-3">
                                        <div class="col-sm-4 text-muted font-size-13">Middle Name:</div>
                                        <div class="col-sm-8 fw-semibold text-dark">{{ $middleName }}</div>
                                    </div>
                                    @endif

                                    <hr class="my-4 opacity-50">

                                    <h6 class="text-muted text-uppercase font-size-11 fw-bold mb-3" style="letter-spacing: 0.5px;">
                                        Account Information
                                    </h6>
                                    
                                    <div class="row mb-3">
                                        <div class="col-sm-4 text-muted font-size-13">Email Address:</div>
                                        <div class="col-sm-8 fw-medium text-break text-dark">{{ $staff->email }}</div>
                                    </div>
                                    
                                    <div class="row mb-0">
                                        <div class="col-sm-4 text-muted font-size-13">Onboarded:</div>
                                        <div class="col-sm-8 fw-medium text-dark">{{ $staff->created_at->format('d M, Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- ADD STAFF MODAL --}}
<div class="modal fade" id="addStaff" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ url('admin/newStaff') }}" class="w-100">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Create New Staff Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter staff full name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Address *</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="staff@business.com" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Access Password *</label>
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
                    <button class="btn btn-success px-4 fw-bold">Create Staff Account</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
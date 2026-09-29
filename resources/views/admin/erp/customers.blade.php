@extends('admin.layout.dashboard')

@section('content')

<div class="row">
	<div class="col-12">
		<div class="page-title-box d-sm-flex align-items-center justify-content-between">
			<h4 class="mb-sm-0 font-size-18">Customers</h4>
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item">Users</li>
					<li class="breadcrumb-item active">Customers</li>
				</ol>
			</div>
		</div>
	</div>
</div>

<div class="row mb-4">
	<div class="col-md-4">
		<div class="card shadow-sm border-0"><div class="card-body"><div class="d-flex align-items-center"><div class="flex-grow-1"><p class="text-muted fw-medium mb-2">Total Customers</p><h3 class="mb-0">{{ $customers->count() }}</h3></div><div class="avatar-sm"><span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-20"><i class="bx bx-user"></i></span></div></div></div></div>
	</div>
	<div class="col-md-4">
		<div class="card shadow-sm border-0"><div class="card-body"><div class="d-flex align-items-center"><div class="flex-grow-1"><p class="text-muted fw-medium mb-2">New This Month</p><h3 class="mb-0">{{ $customers->where('created_at', '>=', now()->startOfMonth())->count() }}</h3></div><div class="avatar-sm"><span class="avatar-title rounded-circle bg-soft-success text-success font-size-20"><i class="bx bx-user-plus"></i></span></div></div></div></div>
	</div>
	<div class="col-md-4">
		<div class="card shadow-sm border-0"><div class="card-body"><div class="d-flex align-items-center"><div class="flex-grow-1"><p class="text-muted fw-medium mb-2">Registered Email Accounts</p><h3 class="mb-0">{{ $customers->whereNotNull('email')->count() }}</h3></div><div class="avatar-sm"><span class="avatar-title rounded-circle bg-soft-info text-info font-size-20"><i class="bx bx-envelope"></i></span></div></div></div></div>
	</div>
</div>

<div class="card shadow-sm border-0">
	<div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between">
		<div><h4 class="card-title mb-0">Customer Directory</h4><p class="text-muted mb-0 small">View registered storefront customers and their account activity.</p></div>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table id="datatable-buttons" class="table table-hover table-bordered align-middle dt-responsive nowrap w-100">
				<thead class="table-light"><tr><th class="text-center" style="width: 55px;">S/N</th><th>Customer</th><th>Email Address</th><th>Orders</th><th>Joined</th><th class="text-center">Actions</th></tr></thead>
				<tbody>
					@forelse($customers as $customer)
					<tr>
						<td class="text-center text-muted">{{ $loop->iteration }}</td>
						<td><div class="d-flex align-items-center"><div class="avatar-xs me-3"><span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-12">{{ strtoupper(substr($customer->name, 0, 1)) }}</span></div><div><h5 class="font-size-14 mb-0 text-dark">{{ $customer->name }}</h5><small class="text-muted">Customer account</small></div></div></td>
						<td>{{ $customer->email }}</td>
						<td><span class="badge bg-soft-primary text-primary">{{ $customer->sales_count }}</span></td>
						<td data-order="{{ optional($customer->created_at)->timestamp }}">{{ optional($customer->created_at)->format('d M, Y') }}</td>
						<td class="text-center"><button class="btn btn-soft-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#viewCustomer{{ $customer->id }}" title="View customer"><i class="mdi mdi-eye-outline font-size-16"></i></button><button class="btn btn-soft-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCustomer{{ $customer->id }}" title="Remove customer"><i class="mdi mdi-trash-can-outline font-size-16"></i></button></td>
					</tr>
					@empty
					<tr><td colspan="6" class="text-center text-muted py-5">No customers have registered yet.</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>

@foreach($customers as $customer)
<div class="modal fade" id="viewCustomer{{ $customer->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow"><div class="modal-header bg-light"><h5 class="modal-title">Customer Details</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="text-center mb-4"><div class="avatar-lg mx-auto mb-3"><span class="avatar-title rounded-circle bg-soft-primary text-primary display-5">{{ strtoupper(substr($customer->name, 0, 1)) }}</span></div><h5 class="mb-1">{{ $customer->name }}</h5><span class="badge rounded-pill bg-soft-primary text-primary">Storefront Customer</span></div><div class="row mb-3"><div class="col-4 text-muted">Email</div><div class="col-8 fw-semibold">{{ $customer->email }}</div></div><div class="row"><div class="col-4 text-muted">Joined</div><div class="col-8 fw-semibold">{{ optional($customer->created_at)->format('d M, Y H:i') }}</div></div></div></div></div></div>
<div class="modal fade" id="deleteCustomer{{ $customer->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><form method="POST" action="{{ url('admin/deleteCustomer') }}" class="w-100">@csrf<input type="hidden" name="customer_id" value="{{ $customer->id }}"><div class="modal-content border-0 shadow"><div class="modal-body text-center p-5"><div class="avatar-lg mx-auto mb-4"><div class="avatar-title bg-soft-danger text-danger display-4 rounded-circle"><i class="mdi mdi-account-remove-outline"></i></div></div><h4 class="text-danger">Remove Customer?</h4><p class="text-muted">You are about to remove <strong>{{ $customer->name }}</strong>'s storefront account.</p><div class="d-flex gap-2 mt-4"><button type="button" class="btn btn-light w-50" data-bs-dismiss="modal">Cancel</button><button class="btn btn-danger w-50">Confirm Deletion</button></div></div></div></form></div></div>
@endforeach


@endsection
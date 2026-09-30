@extends('admin.layout.dashboard')

@section('content')

<div class="row">
	<div class="col-12">
		<div class="page-title-box d-sm-flex align-items-center justify-content-between">
			<h4 class="mb-sm-0 font-size-18">Customers</h4>
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item active">Customers</li>
				</ol>
			</div>
		</div>
	</div>
</div>

<div class="row mb-4">
	<div class="col-md-4">
		<div class="card shadow-sm border-0">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div class="flex-grow-1">
						<p class="text-black fw-medium mb-2">Total Customers</p>
						<h3 class="mb-0">{{ $customers->count() }}</h3>
					</div>
					<div class="avatar-sm">
						<span class="avatar-title rounded-circle bg-soft-primary font-size-20">
							<i class="bx bx-user"></i>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card shadow-sm border-0">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div class="flex-grow-1">
						<p class="text-black fw-medium mb-2">New This Month</p>
						<h3 class="mb-0">{{ $customers->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
					</div>
					<div class="avatar-sm">
						<span class="avatar-title rounded-circle bg-soft-success font-size-20">
							<i class="bx bx-user-plus"></i>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card shadow-sm border-0">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div class="flex-grow-1">
						<p class="text-black fw-medium mb-2">Awaiting Verification</p>
						<h3 class="mb-0">{{ $customers->filter(fn ($customer) => $customer->status !== 'active' || !$customer->email_verified_at)->count() }}</h3>
					</div>
					<div class="avatar-sm">
						<span class="avatar-title rounded-circle bg-soft-info font-size-20">
							<i class="bx bx-envelope"></i>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="card shadow-sm border-0">
	<div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between">
		<div>
			<h4 class="card-title mb-0">Customer Directory</h4>
			<p class="text-muted mb-0 small">View registered storefront customers and their account activity.</p>
		</div>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table id="datatable-buttons" class="table table-hover table-bordered align-middle dt-responsive nowrap w-100">
				<thead class="table-light"><tr><th class="text-center" style="width: 55px;">S/N</th><th>Customer</th><th>Email Address</th><th>Account Status</th><th>Phone</th><th>Orders</th><th>Last Order</th><th>Confirmed Spend</th><th>Joined</th><th class="text-center">Actions</th></tr></thead>
				<tbody>
					@forelse($customers as $customer)
					<tr>
						<td class="text-center text-muted">{{ $loop->iteration }}</td>
						<td>
								<div class="d-flex align-items-center">
									<div class="avatar-xs me-3">
										<span class="avatar-title rounded-circle bg-soft-primary font-size-12">{{ $customer->initials }}</span>
									</div>
								<div>
									<h5 class="font-size-14 mb-0 text-dark">{{ $customer->name }}</h5>
									<small class="text-muted">Customer account</small>
								</div>
							</div>
						</td>
						<td>{{ $customer->email }}</td>
						<td>
							<span class="badge bg-soft-{{ $customer->status === 'active' ? 'success' : 'warning' }} text-{{ $customer->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst($customer->status) }}</span>
							<small class="d-block mt-1 text-muted">{{ $customer->email_verified_at ? 'Email verified' : 'Email unverified' }}</small>
						</td>
						<td>{{ $customer->phone ?: 'Not provided' }}</td>
						<td>
							<span class="badge bg-soft-primary text-primary">{{ $customer->sales_count }}</span>
						</td>
						<td>
							@if($customer->latest_order_reference)
								<strong class="d-block">{{ $customer->latest_order_reference }}</strong>
								<small class="text-muted">{{ \Illuminate\Support\Carbon::parse($customer->latest_order_date)->format('d M, Y') }}</small>
							@else
								<span class="text-muted">No orders</span>
							@endif
						</td>
						<td>₦{{ number_format((float) $customer->confirmed_order_total, 2) }}</td>
						<td data-order="{{ optional($customer->created_at)->timestamp }}">{{ optional($customer->created_at)->format('d M, Y') }}</td>
						<td class="text-center">
							<button class="btn btn-soft-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#viewCustomer{{ $customer->id }}" title="View customer">
								<i class="mdi mdi-eye-outline font-size-16"></i>
							</button>
							<button class="btn btn-soft-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCustomer{{ $customer->id }}" title="Remove customer">
								<i class="mdi mdi-trash-can-outline font-size-16"></i>
							</button>
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="10" class="text-center text-muted py-5">No customers have registered yet.</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>

@foreach($customers as $customer)
<div class="modal fade" id="viewCustomer{{ $customer->id }}" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content border-0 shadow">
			<div class="modal-header border-0 pb-0">
				<div><small class="text-muted text-uppercase">Customer profile</small><h5 class="modal-title mt-1">Account overview</h5></div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body pt-3">
				<div class="d-flex align-items-center gap-3 bg-light rounded p-3 mb-4">
					<div class="avatar-lg flex-shrink-0"><span class="avatar-title rounded-circle bg-soft-primary display-5">{{ $customer->initials }}</span></div>
					<div class="min-w-0">
						<h4 class="mb-1">{{ $customer->name }}</h4>
						<span class="badge bg-soft-primary text-primary">Storefront customer</span>
						<span class="badge bg-soft-{{ $customer->status === 'active' ? 'success' : 'warning' }} text-{{ $customer->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst($customer->status) }}</span>
						<span class="badge bg-soft-{{ $customer->email_verified_at ? 'success' : 'warning' }} text-{{ $customer->email_verified_at ? 'success' : 'warning' }}">{{ $customer->email_verified_at ? 'Email verified' : 'Email unverified' }}</span>
						<div class="d-flex flex-wrap gap-3 mt-2 small">
							<a href="mailto:{{ $customer->email }}" class="text-reset text-decoration-none"><i class="mdi mdi-email-outline me-1"></i>{{ $customer->email }}</a>
							@if($customer->phone)<a href="tel:{{ $customer->phone }}" class="text-reset text-decoration-none"><i class="mdi mdi-phone-outline me-1"></i>{{ $customer->phone }}</a>@endif
						</div>
					</div>
				</div>
				<div class="row g-2 mb-4">
					<div class="col-6 col-md"><div class="border rounded p-3 h-100"><small class="text-muted d-block">All orders</small><strong class="h4 mb-0">{{ $customer->sales_count }}</strong></div></div>
					<div class="col-6 col-md"><div class="border rounded p-3 h-100"><small class="text-muted d-block">Paid</small><strong class="h4 mb-0 text-success">{{ $customer->paid_orders_count }}</strong></div></div>
					<div class="col-6 col-md"><div class="border rounded p-3 h-100"><small class="text-muted d-block">Pending</small><strong class="h4 mb-0 text-warning">{{ $customer->pending_orders_count }}</strong></div></div>
					<div class="col-6 col-md"><div class="border rounded p-3 h-100"><small class="text-muted d-block">Needs attention</small><strong class="h4 mb-0 text-danger">{{ $customer->attention_orders_count }}</strong></div></div>
					<div class="col-12 col-md"><div class="border rounded p-3 h-100"><small class="text-muted d-block">Confirmed spend</small><strong class="h5 mb-0">₦{{ number_format((float) $customer->confirmed_order_total, 2) }}</strong></div></div>
				</div>
				<div class="row g-4">
					<div class="col-lg-5">
						<h6 class="text-uppercase text-muted small mb-3">Contact & account</h6>
						<dl class="row mb-0 small">
							<dt class="col-5 text-muted">Account status</dt><dd class="col-7">{{ ucfirst($customer->status) }}</dd>
							<dt class="col-5 text-muted">Email verified</dt><dd class="col-7">{{ $customer->email_verified_at ? $customer->email_verified_at->format('d M, Y H:i') : 'Not verified' }}</dd>
							<dt class="col-5 text-muted">Saved address</dt><dd class="col-7">{{ $customer->address ?: 'Not provided' }}</dd>
							<dt class="col-5 text-muted">Customer since</dt><dd class="col-7">{{ optional($customer->created_at)->format('d M, Y H:i') }}</dd>
						</dl>
					</div>
					<div class="col-lg-7">
						<h6 class="text-uppercase text-muted small mb-3">Latest order</h6>
						@if($customer->latest_order_reference)
							<div class="bg-light rounded p-3">
								<div class="d-flex flex-wrap justify-content-between gap-2 mb-2">
									<strong>{{ $customer->latest_order_reference }}</strong>
									<strong>₦{{ number_format((float) $customer->latest_order_total, 2) }}</strong>
								</div>
								<div class="d-flex flex-wrap gap-2 mb-2">
									<span class="badge bg-soft-primary text-primary">{{ ucfirst(str_replace('_', ' ', $customer->latest_order_payment_status)) }}</span>
									<span class="badge bg-soft-secondary text-secondary">{{ ucfirst(str_replace('_', ' ', $customer->latest_order_status)) }}</span>
								</div>
								<small class="text-muted d-block">{{ \Illuminate\Support\Carbon::parse($customer->latest_order_date)->format('d M, Y H:i') }}</small>
								@if($customer->latest_delivery_address)<small class="d-block mt-2"><i class="mdi mdi-map-marker-outline me-1"></i>{{ $customer->latest_delivery_address }}</small>@endif
								@if($customer->latest_delivery_phone)<small class="d-block"><i class="mdi mdi-phone-outline me-1"></i>{{ $customer->latest_delivery_phone }}</small>@endif
							</div>
						@else
							<p class="text-muted small mb-0">This customer has not placed an order yet.</p>
						@endif
						<a href="{{ route('customers.orders', $customer) }}" class="btn btn-outline-primary btn-sm mt-3">
							<i class="mdi mdi-format-list-bulleted me-1"></i> View all orders
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="deleteCustomer{{ $customer->id }}" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<form method="POST" action="{{ url('admin/deleteCustomer') }}" class="w-100">
			@csrf
			<input type="hidden" name="customer_id" value="{{ $customer->id }}">
			<div class="modal-content border-0 shadow">
				<div class="modal-body text-center p-5">
					<div class="avatar-lg mx-auto mb-4">
						<div class="avatar-title bg-soft-danger display-4 rounded-circle">
							<i class="mdi mdi-account-remove-outline"></i>
						</div>
					</div>
					<h4 class="text-danger">Remove Customer?</h4>
					<p class="text-muted">You are about to remove <strong>{{ $customer->name }}</strong>'s storefront account.</p>
					<div class="d-flex gap-2 mt-4">
						<button type="button" class="btn btn-light w-50" data-bs-dismiss="modal">Cancel</button>
						<button class="btn btn-danger w-50">Confirm Deletion</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
@endforeach


@endsection
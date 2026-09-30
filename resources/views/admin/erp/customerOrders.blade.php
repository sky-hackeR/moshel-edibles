@extends('admin.layout.dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <div>
                <h4 class="mb-sm-0 font-size-18">Customer Orders</h4>
                <ol class="breadcrumb m-0 mt-1">
                    <li class="breadcrumb-item active">{{ $customer->name }}</li>
                </ol>
            </div>
            <a href="{{ route('customers') }}" class="btn btn-light">
                <i class="mdi mdi-arrow-left me-1"></i> Back to customers
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body d-flex flex-wrap align-items-center gap-3">
        <div class="avatar-lg flex-shrink-0">
            <span class="avatar-title rounded-circle bg-soft-primary display-5">{{ $customer->initials }}</span>
        </div>
        <div class="flex-grow-1">
            <h4 class="mb-1">{{ $customer->name }}</h4>
            <div class="d-flex flex-wrap gap-3 small">
                <a href="mailto:{{ $customer->email }}" class="text-reset text-decoration-none"><i class="mdi mdi-email-outline me-1"></i>{{ $customer->email }}</a>
                @if($customer->phone)
                    <a href="tel:{{ $customer->phone }}" class="text-reset text-decoration-none"><i class="mdi mdi-phone-outline me-1"></i>{{ $customer->phone }}</a>
                @endif
            </div>
        </div>
        <div class="text-md-end">
            <small class="text-muted d-block">{{ $orders->total() }} orders</small>
            <strong>₦{{ number_format((float) $orders->getCollection()->sum('payable_amount'), 2) }}</strong>
            <small class="text-muted d-block">This page total</small>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-bottom">
        <h5 class="card-title mb-0">Order history</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date & reference</th>
                        <th>Items</th>
                        <th>Status</th>
                        <th>Delivery</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <strong class="d-block">{{ $order->reference_no }}</strong>
                                <small class="text-muted">{{ optional($order->created_at)->format('d M, Y H:i') }}</small>
                            </td>
                            <td>
                                @forelse($order->items as $item)
                                    <span class="d-block">{{ optional(optional($item->product)->storeProduct)->store_title ?? optional($item->product)->name ?? 'Unavailable product' }} <small class="text-muted">× {{ $item->quantity }}</small></span>
                                @empty
                                    <span class="text-muted">No item details</span>
                                @endforelse
                            </td>
                            <td>
                                <span class="badge bg-soft-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'failed' ? 'danger' : 'warning') }} text-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'failed' ? 'danger' : 'warning') }}">{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</span>
                                <small class="d-block text-muted mt-1">{{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</small>
                                @if($order->payment_status === 'review')
                                    <small class="d-block text-danger">Needs payment review</small>
                                @endif
                            </td>
                            <td>
                                <span class="d-block">{{ $order->delivery_address ?: 'No address recorded' }}</span>
                                <small class="text-muted">{{ $order->delivery_phone }}</small>
                            </td>
                            <td class="text-end text-nowrap">
                                <strong>₦{{ number_format($order->payable_amount, 2) }}</strong>
                                <small class="d-block text-muted">Items ₦{{ number_format($order->total_amount, 2) }} · Ship ₦{{ number_format($order->shipping_fee, 2) }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">This customer has no orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
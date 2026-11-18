@extends('mail.layout.mail')

@section('preheader', 'New storefront order confirmed: ' . $sale->reference_no)

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge badge-success" style="margin-bottom: 12px; font-size: 11px;">🛍️ New Online Order</span>
        <h1 style="margin: 8px 0 10px; color: #4a1253;">New Storefront Order Received</h1>
        <p style="color: #6d5d70; font-size: 15px; margin: 0 auto; max-width: 480px;">
            A customer payment has been confirmed. 
            @if($sale->order_status === 'payment_review')
                <strong style="color: #c53030;">This order requires an availability and payment review before fulfillment.</strong>
            @else
                Stock has been deducted for fulfillment.
            @endif
        </p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th colspan="2">Order Overview</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #8a7b8e; width: 40%;">Reference</td>
                <td style="font-weight: 700; color: #2a172e; font-family: monospace;">{{ $sale->reference_no }}</td>
            </tr>
            <tr>
                <td style="color: #8a7b8e;">Customer</td>
                <td style="font-weight: 600; color: #2a172e;">
                    {{ optional($sale->customer)->name ?: 'Customer account unavailable' }}<br>
                    <small style="color: #8a7b8e; font-weight: 400;">{{ optional($sale->customer)->email ?: 'no email on record' }}</small>
                </td>
            </tr>
            <tr>
                <td style="color: #8a7b8e;">Shipping Fee</td>
                <td style="font-weight: 600; color: #2a172e;">₦{{ number_format($sale->shipping_fee, 2) }}</td>
            </tr>
            <tr>
                <td style="color: #8a7b8e;">Total Paid</td>
                <td style="font-weight: 800; color: #581a62; font-size: 16px;">₦{{ number_format($sale->payable_amount, 2) }}</td>
            </tr>
            <tr>
                <td style="color: #8a7b8e;">Order Status</td>
                <td>
                    <span class="badge {{ $sale->order_status === 'payment_review' ? 'badge-warning' : 'badge-purple' }}">
                        {{ ucfirst(str_replace('_', ' ', $sale->order_status)) }}
                    </span>
                </td>
            </tr>
            @if($sale->paystack_amount !== null)
            <tr>
                <td style="color: #8a7b8e;">Paystack Charge</td>
                <td style="font-weight: 600; color: #2a172e;">{{ $sale->paystack_currency }} {{ number_format($sale->paystack_amount / 100, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <h3 style="color: #581a62; margin: 26px 0 12px; font-size: 13px; letter-spacing: 0.8px;">Ordered Items</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Item</th>
                <th style="text-align: center; width: 60px;">Qty</th>
                <th style="text-align: right; width: 110px;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td style="font-weight: 600; color: #2a172e;">{{ optional($item->product)->name ?: 'Unavailable product' }}</td>
                <td style="text-align: center; color: #718096; font-weight: 600;">{{ $item->quantity }}</td>
                <td style="text-align: right; font-weight: 700; color: #581a62;">₦{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="alert-box" style="margin-top: 24px;">
        <p style="margin: 0 0 4px; font-weight: 700; color: #581a62; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">📍 Delivery Details</p>
        <p style="margin: 0; color: #4f3f52; font-size: 14px; line-height: 1.5;">
            {{ $sale->delivery_address }}<br>
            <strong>Contact Phone:</strong> {{ $sale->delivery_phone }}
        </p>
    </div>

    <div style="margin: 32px 0 8px; text-align: center;">
        <a href="{{ url('/admin/salesHistory') }}" class="button" target="_blank">
            Open Order in ERP &rarr;
        </a>
    </div>
@endsection

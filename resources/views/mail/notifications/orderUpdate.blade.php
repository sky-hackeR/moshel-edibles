@extends('mail.layout.mail')

@section('preheader', 'Order update for ' . $sale->reference_no)

@section('content')
    @if($sale->payment_status === 'review')
        <div style="text-align: center; margin-bottom: 24px;">
            <span class="badge badge-warning" style="margin-bottom: 12px; font-size: 11px;">⚠️ Verification Pending</span>
            <h1 style="margin: 8px 0 10px; color: #4a1253;">Payment Requires Review</h1>
            <p style="color: #6d5d70; font-size: 15px; margin: 0 auto; max-width: 480px;">
                Hello <strong>{{ $name }}</strong>, Paystack reported a successful transaction, but the amount or currency did not match this order. Our team must verify the transaction before fulfillment.
            </p>
        </div>

        <div class="alert-box alert-box-warning">
            <p style="margin: 0; font-weight: 700; color: #92400e;">Please do not send another payment yet.</p>
            <p style="margin: 6px 0 0; font-size: 13px; color: #78350f;">
                We will contact you after reviewing the transaction to ensure everything is resolved smoothly.
            </p>
        </div>
    @elseif($sale->order_status === 'payment_review')
        <div style="text-align: center; margin-bottom: 24px;">
            <span class="badge badge-purple" style="margin-bottom: 12px; font-size: 11px;">🔍 Order Under Review</span>
            <h1 style="margin: 8px 0 10px; color: #4a1253;">Payment Received, Order Under Review</h1>
            <p style="color: #6d5d70; font-size: 15px; margin: 0 auto; max-width: 480px;">
                Hello <strong>{{ $name }}</strong>, we received your payment, but one or more items need an availability check before we can confirm fulfillment.
            </p>
        </div>

        <div class="alert-box">
            <p style="margin: 0; font-weight: 700; color: #581a62;">No further payment is needed.</p>
            <p style="margin: 6px 0 0; font-size: 13px; color: #736277;">
                Our team will review this order and contact you about the next step shortly.
            </p>
        </div>
    @else
        <div style="text-align: center; margin-bottom: 24px;">
            <span class="badge badge-success" style="margin-bottom: 12px; font-size: 11px;">🎉 Order Confirmed</span>
            <h1 style="margin: 8px 0 10px; color: #4a1253;">Your Order Is Confirmed</h1>
            <p style="color: #6d5d70; font-size: 15px; margin: 0 auto; max-width: 480px;">
                Thank you, <strong>{{ $name }}</strong>! Your payment was confirmed and our bakery team has begun preparing your freshly baked order.
            </p>
        </div>
    @endif

    <table class="data-table">
        <thead>
            <tr>
                <th colspan="2">Order Summary</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #8a7b8e; width: 45%;">Order Reference</td>
                <td style="font-weight: 700; color: #2a172e; font-family: monospace; font-size: 15px;">{{ $sale->reference_no }}</td>
            </tr>
            <tr>
                <td style="color: #8a7b8e;">Shipping Fee</td>
                <td style="font-weight: 600; color: #2a172e;">₦{{ number_format($sale->shipping_fee, 2) }}</td>
            </tr>
            <tr>
                <td style="color: #8a7b8e;">Order Total</td>
                <td style="font-weight: 800; color: #581a62; font-size: 16px;">₦{{ number_format($sale->payable_amount, 2) }}</td>
            </tr>
            <tr>
                <td style="color: #8a7b8e;">Payment Status</td>
                <td>
                    <span class="badge {{ $sale->payment_status === 'paid' ? 'badge-success' : 'badge-warning' }}">
                        {{ ucfirst($sale->payment_status) }}
                    </span>
                </td>
            </tr>
        </tbody>
    </table>

    <div style="margin: 32px 0 12px; text-align: center;">
        <a href="{{ $accountUrl }}" class="button" target="_blank">
            View Order in Account &rarr;
        </a>
    </div>
@endsection

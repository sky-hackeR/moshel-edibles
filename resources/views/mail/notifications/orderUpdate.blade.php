@extends('mail.layout.mail')

@section('preheader', 'Payment update for order ' . $sale->reference_no)

@section('content')
    @if($sale->payment_status === 'review')
        <h2>Payment Requires Review</h2>
        <p>Hello {{ $name }}, Paystack reported a successful transaction, but the amount or currency did not match this order. Our team must verify the transaction before fulfillment.</p>
        <div class="alert-box">
            <p style="margin: 0; color: #64236f; font-weight: 700;">Please do not send another payment yet.</p>
            <p style="margin: 6px 0 0;">We will contact you after reviewing the transaction.</p>
        </div>
    @elseif($sale->order_status === 'payment_review')
        <h2>Payment received, order under review</h2>
        <p>Hello {{ $name }}, we received your payment, but one or more items need an availability check before we can confirm fulfillment.</p>
        <div class="alert-box">
            <p style="margin: 0; color: #64236f; font-weight: 700;">No further payment is needed.</p>
            <p style="margin: 6px 0 0;">Our team will review this order and contact you about the next step.</p>
        </div>
    @else
        <h2>Your order is confirmed</h2>
        <p>Thank you, {{ $name }}. Your payment was confirmed and your order is being prepared.</p>
    @endif

    <table class="data-table">
        <tbody>
            <tr>
                <th scope="row">Order reference</th>
                <td>{{ $sale->reference_no }}</td>
            </tr>
            <tr>
                <th scope="row">Order total</th>
                <td>NGN {{ number_format($sale->payable_amount, 2) }}</td>
            </tr>
            <tr>
                <th scope="row">Payment status</th>
                <td>{{ ucfirst($sale->payment_status) }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin: 28px 0 8px; text-align: center;">
        <a href="{{ $accountUrl }}" class="button">View your account</a>
    </div>
@endsection
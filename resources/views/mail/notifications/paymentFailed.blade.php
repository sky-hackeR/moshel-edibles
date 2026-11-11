@extends('mail.layout.mail')

@section('preheader', 'Payment status update for order ' . $sale->reference_no)

@section('content')
    <h2>Payment was not completed</h2>
    <p>Hello {{ $name }}, we could not confirm a successful payment for this order.</p>

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
        </tbody>
    </table>

    <div class="alert-box">
        <p style="margin: 0;">If your account was charged, please contact us with the order reference before trying again.</p>
    </div>

    <div style="margin: 28px 0 8px; text-align: center;">
        <a href="{{ $checkoutUrl }}" class="button">Return to checkout</a>
    </div>
@endsection
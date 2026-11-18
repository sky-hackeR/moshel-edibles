@extends('mail.layout.mail')

@section('preheader', 'Payment status update for order ' . $sale->reference_no)

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge badge-danger" style="margin-bottom: 12px; font-size: 11px;">⚠️ Payment Incomplete</span>
        <h1 style="margin: 8px 0 10px; color: #4a1253;">Payment Was Not Completed</h1>
        <p style="color: #6d5d70; font-size: 15px; margin: 0 auto; max-width: 480px;">
            Hello <strong>{{ $name }}</strong>, we could not confirm a successful payment for your order. Don't worry, your cart items are safe.
        </p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th colspan="2">Transaction Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #8a7b8e; width: 45%;">Order Reference</td>
                <td style="font-weight: 700; color: #2a172e; font-family: monospace;">{{ $sale->reference_no }}</td>
            </tr>
            <tr>
                <td style="color: #8a7b8e;">Order Total</td>
                <td style="font-weight: 800; color: #2a172e; font-size: 16px;">₦{{ number_format($sale->payable_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="alert-box alert-box-warning">
        <p style="margin: 0; font-weight: 700; color: #92400e;">Were you charged by your bank?</p>
        <p style="margin: 6px 0 0; font-size: 13px; color: #78350f;">
            If your account was debited, please contact our support team with your order reference (<strong>{{ $sale->reference_no }}</strong>) before attempting another payment so we can locate the transaction.
        </p>
    </div>

    <div style="margin: 32px 0 12px; text-align: center;">
        <a href="{{ $checkoutUrl }}" class="button" target="_blank">
            Return to Checkout &rarr;
        </a>
    </div>
@endsection

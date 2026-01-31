@extends('mail.layout.mail')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="display: inline-block; background-color: #f0fdf4; padding: 15px; border-radius: 50%; margin-bottom: 15px;">
            <span style="font-size: 30px;">💰</span>
        </div>
        <h2 style="color: #15803d; font-size: 24px; margin: 0;">Transaction Confirmed</h2>
        <p style="color: #718096;">A new sale has been processed successfully.</p>
    </div>

    <div style="background-color: #f8f9fa; border-radius: 12px; padding: 25px; margin-bottom: 30px; border: 1px solid #edf2f7;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td style="padding-bottom: 10px; color: #718096; font-size: 13px; text-transform: uppercase;">Reference</td>
                <td style="padding-bottom: 10px; text-align: right; font-weight: 700; color: #2d3748;">{{ $sale->reference_no }}</td>
            </tr>
            <tr>
                <td style="padding-bottom: 10px; color: #718096; font-size: 13px; text-transform: uppercase;">Terminal Operator</td>
                <td style="padding-bottom: 10px; text-align: right; font-weight: 600; color: #2d3748;">{{ $seller->name }}</td>
            </tr>
            <tr>
                <td style="padding-bottom: 10px; color: #718096; font-size: 13px; text-transform: uppercase;">Payment Method</td>
                <td style="padding-bottom: 10px; text-align: right;">
                    <span style="background: #e2e8f0; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 700;">{{ strtoupper($sale->payment_method) }}</span>
                </td>
            </tr>
            @if($sale->discount_amount > 0)
            <tr>
                <td style="padding-top: 10px; color: #e53e3e; font-size: 13px;">Discount Applied</td>
                <td style="padding-top: 10px; text-align: right; color: #e53e3e; font-weight: 600;">-₦{{ number_format($sale->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding-top: 15px; border-top: 1px solid #e2e8f0; font-weight: 700; font-size: 18px; color: #2d3748;">Total Paid</td>
                <td style="padding-top: 15px; border-top: 1px solid #e2e8f0; text-align: right; font-weight: 800; font-size: 22px; color: #556ee6;">
                    ₦{{ number_format($sale->payable_amount, 2) }}
                </td>
            </tr>
        </table>
    </div>

    <h3 style="font-size: 14px; text-transform: uppercase; color: #a0aec0; letter-spacing: 1px; margin-bottom: 15px;">Itemized Breakdown</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Product</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td style="font-size: 14px; color: #2d3748;">{{ $item->product->name ?? 'Unknown Product' }}</td>
                <td style="text-align: center; color: #718096;">{{ $item->quantity }}</td>
                <td style="text-align: right; font-weight: 600; color: #2d3748;">₦{{ number_format($item->subtotal ?? ($item->quantity * $item->unit_price), 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="text-align: center; margin-top: 40px;">
        <a href="{{ url('/admin/salesHistory') }}" class="button" style="background-color: #556ee6;">
            Review Sales Log
        </a>
    </div>
@endsection
@extends('mail.layout.mail')

@section('preheader', 'Sale confirmed: #' . $sale->reference_no . ' — ₦' . number_format($sale->payable_amount, 2))

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge-success" style="display: inline-block; margin-bottom: 10px;">POS Transaction Confirmed</span>
        <h2 style="margin: 0 0 6px; font-size: 22px; color: #2a172e; font-weight: 700;">Point of Sale Receipt</h2>
        <p style="margin: 0; color: #6c5a70; font-size: 14px;">A new retail transaction has been finalized successfully.</p>
    </div>

    <!-- Receipt Header Card -->
    <div style="background-color: #fbf6fc; border-radius: 12px; padding: 20px 22px; margin-bottom: 24px; border: 1px solid #ebddec;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td style="padding-bottom: 10px; color: #6c5a70; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Reference ID</td>
                <td style="padding-bottom: 10px; text-align: right; font-weight: 700; color: #2a172e; font-family: 'Courier New', monospace;">{{ $sale->reference_no }}</td>
            </tr>
            <tr>
                <td style="padding-bottom: 10px; color: #6c5a70; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Terminal Operator</td>
                <td style="padding-bottom: 10px; text-align: right; font-weight: 600; color: #2a172e;">{{ $seller->name }}</td>
            </tr>
            <tr>
                <td style="padding-bottom: 10px; color: #6c5a70; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Payment Method</td>
                <td style="padding-bottom: 10px; text-align: right;">
                    <span class="badge-purple" style="font-size: 11px;">{{ strtoupper($sale->payment_method) }}</span>
                </td>
            </tr>
            @if($sale->discount_amount > 0)
            <tr>
                <td style="padding-top: 6px; color: #b91c1c; font-size: 13px;">Discount Applied</td>
                <td style="padding-top: 6px; text-align: right; color: #b91c1c; font-weight: 600;">-₦{{ number_format($sale->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding-top: 14px; border-top: 1px dashed #ebddec; font-weight: 700; font-size: 16px; color: #2a172e;">Total Amount Paid</td>
                <td style="padding-top: 14px; border-top: 1px dashed #ebddec; text-align: right; font-weight: 800; font-size: 22px; color: #581a62;">
                    ₦{{ number_format($sale->payable_amount, 2) }}
                </td>
            </tr>
        </table>
    </div>

    <h3 style="font-size: 12px; text-transform: uppercase; color: #6c5a70; letter-spacing: 0.8px; margin: 0 0 12px; font-weight: 700;">Itemized Breakdown</h3>
    <table class="data-table" style="margin-bottom: 28px;">
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
                <td style="font-size: 14px; color: #2a172e; font-weight: 600;">{{ $item->product->name ?? 'Unknown Product' }}</td>
                <td style="text-align: center; color: #6c5a70;">{{ $item->quantity }}</td>
                <td style="text-align: right; font-weight: 700; color: #2a172e;">₦{{ number_format($item->subtotal ?? ($item->quantity * $item->unit_price), 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="text-align: center;">
        <a href="{{ url('/admin/salesHistory') }}" class="button">
            View Sales Log
        </a>
    </div>
@endsection

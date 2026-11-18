@extends('mail.layout.mail')

@section('preheader', 'Price update alert: ' . $product->name . ' changed from ₦' . number_format($oldPrice, 2) . ' to ₦' . number_format($product->selling_price, 2))

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge-purple" style="display: inline-block; margin-bottom: 10px;">Pricing Update</span>
        <h2 style="margin: 0 0 6px; font-size: 22px; color: #2a172e; font-weight: 700;">Price Modification Alert</h2>
        <p style="margin: 0; color: #6c5a70; font-size: 14px;">The retail selling price for a product has been updated in the catalogue.</p>
    </div>

    <!-- Comparison Card -->
    <div style="background-color: #ffffff; border: 1px solid #ebddec; border-radius: 12px; overflow: hidden; margin-bottom: 24px; box-shadow: 0 2px 8px rgba(88, 26, 98, 0.04);">
        <div style="padding: 14px; background-color: #fbf6fc; border-bottom: 1px solid #ebddec; text-align: center;">
            <strong style="color: #2a172e; font-size: 16px;">{{ $product->name }}</strong>
        </div>
        <table width="100%" cellpadding="0" cellspacing="0" style="table-layout: fixed;">
            <tr>
                <td width="50%" style="padding: 20px 15px; border-right: 1px solid #ebddec; text-align: center;">
                    <p style="margin: 0 0 4px; font-size: 11px; text-transform: uppercase; color: #6c5a70; font-weight: 700; letter-spacing: 0.5px;">Previous Price</p>
                    <h3 style="margin: 0; color: #6c5a70; text-decoration: line-through; font-size: 18px; font-weight: 600;">₦{{ number_format($oldPrice, 2) }}</h3>
                </td>
                <td width="50%" style="padding: 20px 15px; text-align: center; background-color: #faf5fb;">
                    <p style="margin: 0 0 4px; font-size: 11px; text-transform: uppercase; color: #ce278c; font-weight: 700; letter-spacing: 0.5px;">New Price</p>
                    <h3 style="margin: 0; color: #581a62; font-size: 22px; font-weight: 800;">₦{{ number_format($product->selling_price, 2) }}</h3>
                </td>
            </tr>
        </table>
    </div>

    <table class="data-table" style="margin-bottom: 24px;">
        <thead>
            <tr>
                <th colspan="2" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.6px;">Change Metadata</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #6c5a70; width: 40%; font-weight: 500;">Authorized By</td>
                <td style="font-weight: 600; color: #2a172e;">{{ $user->name }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Effective Timestamp</td>
                <td style="font-size: 13px; color: #2a172e;">{{ now()->format('d M, Y | h:i A') }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Price Variance</td>
                <td style="font-weight: 700; font-size: 15px; color: {{ $product->selling_price > $oldPrice ? '#047857' : '#b91c1c' }};">
                    {{ $product->selling_price > $oldPrice ? '+' : '-' }}₦{{ number_format(abs($product->selling_price - $oldPrice), 2) }}
                    <span style="font-size: 12px; font-weight: 500; color: #6c5a70;">
                        ({{ $oldPrice > 0 ? number_format((($product->selling_price - $oldPrice) / $oldPrice) * 100, 1) : '100' }}%)
                    </span>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="alert-box" style="margin-bottom: 28px; text-align: center;">
        <p style="margin: 0; font-size: 12px; color: #581a62; line-height: 1.5;">
            <strong>Immediate Effect:</strong> This update is live across the storefront, POS checkout counters, and receipt generation.
        </p>
    </div>

    <div style="text-align: center;">
        <a href="{{ url('/admin/products') }}" class="button">
            View Product Catalogue
        </a>
    </div>
@endsection

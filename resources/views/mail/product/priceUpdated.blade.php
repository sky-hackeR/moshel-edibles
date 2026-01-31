@extends('mail.layout.mail')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="display: inline-block; background-color: #ecfdf5; padding: 15px; border-radius: 50%; margin-bottom: 15px;">
            <span style="font-size: 30px;">🏷️</span>
        </div>
        <h2 style="color: #059669; font-size: 24px; margin: 0;">Price Modification Alert</h2>
        <p style="color: #718096;">The retail value for a product has been updated in the system.</p>
    </div>

    <div style="background-color: #ffffff; border: 1px solid #edf2f7; border-radius: 12px; overflow: hidden; margin-bottom: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="padding: 15px; background-color: #f8f9fa; border-bottom: 1px solid #edf2f7; text-align: center;">
            <strong style="color: #2d3748; font-size: 16px;">{{ $product->name }}</strong>
        </div>
        <div style="padding: 20px; display: flex; text-align: center;">
            <table width="100%">
                <tr>
                    <td width="50%" style="padding: 10px; border-right: 1px solid #edf2f7;">
                        <p style="margin: 0; font-size: 11px; text-transform: uppercase; color: #a0aec0; font-weight: 700;">Previous Price</p>
                        <h3 style="margin: 5px 0; color: #718096; text-decoration: line-through;">₦{{ number_format($oldPrice, 2) }}</h3>
                    </td>
                    <td width="50%" style="padding: 10px;">
                        <p style="margin: 0; font-size: 11px; text-transform: uppercase; color: #34c38f; font-weight: 700;">New Selling Price</p>
                        <h3 style="margin: 5px 0; color: #059669; font-size: 20px; font-weight: 800;">₦{{ number_format($product->selling_price, 2) }}</h3>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th colspan="2">Change Metadata</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #718096; width: 40%;">Authorized By</td>
                <td style="font-weight: 600; color: #2d3748;">{{ $user->name }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">Effective Date</td>
                <td style="font-weight: 600; color: #2d3748;">{{ now()->format('d M, Y | h:i A') }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">Price Difference</td>
                <td style="font-weight: 700; color: {{ $product->selling_price > $oldPrice ? '#059669' : '#e53e3e' }};">
                    {{ $product->selling_price > $oldPrice ? '+' : '-' }} 
                    ₦{{ number_format(abs($product->selling_price - $oldPrice), 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <div style="background-color: #ebf8ff; padding: 15px; border-radius: 8px; margin-top: 20px;">
        <p style="margin: 0; font-size: 12px; color: #2b6cb0; text-align: center;">
            <strong>Note:</strong> This change is live. All new POS transactions and digital receipts will reflect the updated pricing immediately.
        </p>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ url('/admin/products') }}" class="button" style="background-color: #34c38f;">
            Verify Pricing
        </a>
    </div>
@endsection
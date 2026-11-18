@extends('mail.layout.mail')

@section('preheader', '[PRODUCTION HALTED] Insufficient stock for ' . $product->name . ': missing ' . $ingredientName)

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge-danger" style="display: inline-block; margin-bottom: 10px;">Production Halted</span>
        <h2 style="margin: 0 0 6px; font-size: 22px; color: #b91c1c; font-weight: 700;">Ingredient Shortage Detected</h2>
        <p style="margin: 0; color: #6c5a70; font-size: 14px;">The batch run could not proceed due to insufficient raw materials on hand.</p>
    </div>

    <div class="alert-box-danger" style="margin-bottom: 24px;">
        <p style="margin: 0 0 4px; color: #b91c1c; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Automated Safety Lock</p>
        <p style="margin: 0; font-size: 13px; color: #991b1b; line-height: 1.5;">
            Production of <strong>{{ $product->name }}</strong> was paused automatically to protect physical inventory accuracy from negative balances.
        </p>
    </div>

    <table class="data-table" style="margin-bottom: 24px;">
        <thead>
            <tr>
                <th colspan="2" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.6px;">Shortage Breakdown</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #6c5a70; width: 40%; font-weight: 500;">Missing Resource</td>
                <td style="font-weight: 700; color: #2a172e; font-size: 15px;">{{ $ingredientName }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Deficit Amount</td>
                <td style="font-weight: 800; color: #b91c1c; font-size: 16px;">
                    {{ number_format($needed, 2) }} units required
                </td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Impacted Recipe / Product</td>
                <td style="font-weight: 600; color: #581a62;">{{ $product->name }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Attempted Timestamp</td>
                <td style="font-size: 13px; color: #6c5a70;">{{ now()->format('d M, Y | h:i A') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="alert-box" style="margin-bottom: 28px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #581a62; line-height: 1.5;">
            <strong>Next Step:</strong> Record a <strong>Stock In</strong> for <strong>{{ $ingredientName }}</strong> to replenish the pantry and resume production.
        </p>
    </div>

    <div style="text-align: center;">
        <a href="{{ url('/admin/stockIn') }}" class="button" style="background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%); box-shadow: 0 4px 12px rgba(185, 28, 28, 0.25);">
            Restock Materials Now
        </a>
    </div>
@endsection

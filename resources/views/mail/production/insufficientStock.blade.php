@extends('mail.layout.mail')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="display: inline-block; background-color: #fff5f5; padding: 15px; border-radius: 50%; margin-bottom: 15px; border: 2px solid #feb2b2;">
            <span style="font-size: 30px;">🛑</span>
        </div>
        <h2 style="color: #c53030; font-size: 24px; margin: 0;">Production Blocked</h2>
        <p style="color: #718096;">Insufficient raw materials to complete the requested batch.</p>
    </div>

    <div class="alert-box" style="background-color: #fff5f5; border-left: 4px solid #c53030;">
        <p style="margin: 0; color: #9b2c2c; font-size: 14px; font-weight: 600;">
            Critical Shortage Detected
        </p>
        <p style="margin: 5px 0 0; font-size: 13px; color: #c53030;">
            The system has halted the production of <strong>{{ $product->name }}</strong> to prevent inventory discrepancies.
        </p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th colspan="2">Shortage Specification</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #718096; width: 40%;">Missing Resource</td>
                <td style="font-weight: 700; color: #2d3748;">{{ $ingredientName }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">Deficit Amount</td>
                <td style="font-weight: 800; color: #e53e3e; font-size: 16px;">
                    {{ number_format($needed, 2) }} units
                </td>
            </tr>
            <tr>
                <td style="color: #718096;">Impacted Product</td>
                <td style="font-weight: 600; color: #4a5568;">{{ $product->name }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">Incident Date</td>
                <td style="font-size: 13px; color: #a0aec0;">{{ now()->format('d M, Y | h:i A') }}</td>
            </tr>
        </tbody>
    </table>

    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 20px; text-align: center; border: 1px dashed #cbd5e0;">
        <p style="margin: 0; font-size: 13px; color: #4a5568;">
            <strong>Next Step:</strong> Please record a "Stock In" for <strong>{{ $ingredientName }}</strong>. Once the inventory is replenished, the production run can be restarted.
        </p>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ url('/admin/stockIn') }}" class="button" style="background-color: #c53030;">
            Restock Now
        </a>
    </div>
@endsection
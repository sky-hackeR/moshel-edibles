@extends('mail.layout.mail')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="display: inline-block; background-color: #fffaf0; padding: 15px; border-radius: 50%; margin-bottom: 15px; border: 2px solid #fbd38d;">
            <span style="font-size: 30px;">🧪</span>
        </div>
        <h2 style="color: #c05621; font-size: 24px; margin: 0;">Recipe Formula Modified</h2>
        <p style="color: #718096;">The raw material requirements for a product have been updated.</p>
    </div>

    <div class="alert-box" style="background-color: #fffaf0; border-left: 4px solid #f6ad55;">
        <p style="margin: 0; color: #9c4221; font-size: 14px; font-weight: 600;">
            ⚠️ Inventory Impact Notice
        </p>
        <p style="margin: 5px 0 0; font-size: 13px; color: #7b341e;">
            This change affects future ingredient deductions. Please verify that the new ratios align with physical production to prevent stock-on-hand errors.
        </p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th colspan="2">Modification Audit</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #718096; width: 40%;">Product Affected</td>
                <td style="font-weight: 700; color: #2d3748;">{{ $recipe->product->name }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">Modified By</td>
                <td style="font-weight: 600; color: #2d3748;">{{ $user->name }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">Account Email</td>
                <td style="font-size: 13px; color: #4a5568;">{{ $user->email }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">Change Timestamp</td>
                <td style="font-size: 13px; color: #a0aec0;">{{ now()->format('M d, Y | h:i A') }}</td>
            </tr>
        </tbody>
    </table>

    <div style="background-color: #f7fafc; padding: 20px; border-radius: 8px; margin-top: 20px; border: 1px solid #edf2f7; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #4a5568; line-height: 1.5;">
            <strong>Note:</strong> Previous production records will retain their original costs. This update only applies to batches finalized after this timestamp.
        </p>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ url('/admin/recipes') }}" class="button" style="background-color: #f6ad55;">
            Verify New Ratios
        </a>
    </div>
@endsection
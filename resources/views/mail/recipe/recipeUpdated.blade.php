@extends('mail.layout.mail')

@section('preheader', '[RECIPE UPDATE] Formulation modified for ' . $recipe->product->name)

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge-purple" style="display: inline-block; margin-bottom: 10px;">Recipe Formulation Update</span>
        <h2 style="margin: 0 0 6px; font-size: 22px; color: #2a172e; font-weight: 700;">Recipe Formula Modified</h2>
        <p style="margin: 0; color: #6c5a70; font-size: 14px;">The raw material requirements and ratios for a bakery product have been updated.</p>
    </div>

    <div class="alert-box-warning" style="margin-bottom: 24px;">
        <p style="margin: 0 0 4px; color: #b45309; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Inventory Impact Notice</p>
        <p style="margin: 0; font-size: 13px; color: #92400e; line-height: 1.5;">
            This change alters future ingredient deduction calculations. Please ensure physical baking ratios match this configuration.
        </p>
    </div>

    <table class="data-table" style="margin-bottom: 24px;">
        <thead>
            <tr>
                <th colspan="2" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.6px;">Modification Audit</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #6c5a70; width: 40%; font-weight: 500;">Product Affected</td>
                <td style="font-weight: 700; color: #2a172e; font-size: 15px;">{{ $recipe->product->name }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Modified By</td>
                <td style="font-weight: 600; color: #2a172e;">{{ $user->name }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">User Account</td>
                <td style="font-size: 13px; color: #581a62;">{{ $user->email }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Change Timestamp</td>
                <td style="font-size: 13px; color: #6c5a70;">{{ now()->format('M d, Y | h:i A') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="alert-box" style="margin-bottom: 28px; text-align: center;">
        <p style="margin: 0; font-size: 12px; color: #581a62; line-height: 1.5;">
            <strong>Note:</strong> Historical production logs retain their original unit costs. This formula update only applies to future batches.
        </p>
    </div>

    <div style="text-align: center;">
        <a href="{{ url('/admin/recipes') }}" class="button">
            Verify Recipe Ratios
        </a>
    </div>
@endsection

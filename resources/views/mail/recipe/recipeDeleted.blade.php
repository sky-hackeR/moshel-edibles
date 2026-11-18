@extends('mail.layout.mail')

@section('preheader', '[RECIPE DELETED] Production formula permanently removed: ' . $recipeName)

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge-danger" style="display: inline-block; margin-bottom: 10px;">Recipe Formulation Deleted</span>
        <h2 style="margin: 0 0 6px; font-size: 22px; color: #b91c1c; font-weight: 700;">Production Formula Removed</h2>
        <p style="margin: 0; color: #6c5a70; font-size: 14px;">A production recipe formula has been permanently deleted from the active registry.</p>
    </div>

    <div class="alert-box-danger" style="margin-bottom: 24px;">
        <p style="margin: 0 0 4px; color: #b91c1c; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Production Capability Disabled</p>
        <p style="margin: 0; font-size: 13px; color: #991b1b; line-height: 1.5;">
            The system cannot compute ingredient deductions for this product. <strong>Batch production runs will be blocked</strong> until a new formula is assigned.
        </p>
    </div>

    <table class="data-table" style="margin-bottom: 24px;">
        <thead>
            <tr>
                <th colspan="2" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.6px;">Deletion Audit</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #6c5a70; width: 40%; font-weight: 500;">Recipe Target</td>
                <td style="font-weight: 700; color: #2a172e; font-size: 15px;">{{ $recipeName }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Action Performed By</td>
                <td style="font-weight: 600; color: #2a172e;">{{ $user->name }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">User Account</td>
                <td style="font-size: 13px; color: #581a62;">{{ $user->email }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Deletion Timestamp</td>
                <td style="font-size: 13px; color: #6c5a70;">{{ now()->format('d M, Y | h:i A') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="alert-box" style="margin-bottom: 28px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #581a62; line-height: 1.5;">
            <strong>Immediate Action:</strong> If this recipe was deleted inadvertently, please rebuild the ingredient requirements to restore production functionality.
        </p>
    </div>

    <div style="text-align: center;">
        <a href="{{ url('/admin/recipes') }}" class="button">
            Rebuild Recipe
        </a>
    </div>
@endsection

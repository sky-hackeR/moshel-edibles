@extends('mail.layout.mail')

@section('preheader', '[DATA INTEGRITY] Deletion blocked for ingredient: ' . $ingredient->name)

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge-danger" style="display: inline-block; margin-bottom: 10px;">Integrity Guard Active</span>
        <h2 style="margin: 0 0 6px; font-size: 22px; color: #b91c1c; font-weight: 700;">Ingredient Deletion Intercepted</h2>
        <p style="margin: 0; color: #6c5a70; font-size: 14px;">An unauthorized or restricted ingredient deletion attempt was blocked.</p>
    </div>

    <div class="alert-box-danger" style="margin-bottom: 24px;">
        <p style="margin: 0 0 4px; color: #b91c1c; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Active Recipe Dependency</p>
        <p style="margin: 0; font-size: 13px; color: #991b1b; line-height: 1.5;">
            <strong>Action Blocked:</strong> This raw material is actively linked to production recipes. Deleting it would corrupt recipe formulations and batch cost analytics.
        </p>
    </div>

    <table class="data-table" style="margin-bottom: 24px;">
        <thead>
            <tr>
                <th colspan="2" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.6px;">Incident Report</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #6c5a70; width: 35%; font-weight: 500;">Target Resource</td>
                <td style="font-weight: 700; color: #2a172e; font-size: 15px;">{{ $ingredient->name }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Attempted By</td>
                <td style="font-weight: 600; color: #2a172e;">{{ $user->name }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">User Account</td>
                <td style="font-size: 13px; color: #581a62;">{{ $user->email }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Interception Rule</td>
                <td style="font-weight: 600; color: #b91c1c;">{{ $reason }}</td>
            </tr>
        </tbody>
    </table>

    <div class="alert-box" style="margin-bottom: 28px; text-align: center;">
        <p style="margin: 0; font-size: 12px; color: #581a62; line-height: 1.5;">
            <strong>Note:</strong> Data integrity remains intact. To deprecate this ingredient, first remove it from any associated <strong>Product Recipes</strong>.
        </p>
    </div>

    <div style="text-align: center;">
        <a href="{{ url('/admin/ingredients') }}" class="button">
            Review Ingredients
        </a>
    </div>
@endsection

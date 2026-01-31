@extends('mail.layout.mail')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="display: inline-block; background-color: #fff5f5; padding: 15px; border-radius: 50%; margin-bottom: 15px; border: 2px solid #feb2b2;">
            <span style="font-size: 30px;">🚫</span>
        </div>
        <h2 style="color: #c53030; font-size: 24px; margin: 0;">Integrity Guard Intervention</h2>
        <p style="color: #718096;">An unauthorized or restricted deletion attempt was intercepted.</p>
    </div>

    <div class="alert-box" style="background-color: #fff5f5; border-left: 4px solid #c53030;">
        <p style="margin: 0; color: #9b2c2c; font-size: 14px; font-weight: 600;">
            🛡️ System Protection Active
        </p>
        <p style="margin: 5px 0 0; font-size: 13px; color: #c53030;">
            <strong>Action Blocked:</strong> The ingredient below is linked to active recipes and cannot be removed without corrupting production data.
        </p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th colspan="2">Incident Report</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #718096; width: 35%;">Target Resource</td>
                <td style="font-weight: 600; color: #2d3748;">{{ $ingredient->name }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">Attempted By</td>
                <td style="font-weight: 600; color: #2d3748;">{{ $user->name }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">User Account</td>
                <td style="font-size: 13px; color: #4a5568;">{{ $user->email }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">Blocking Logic</td>
                <td style="font-weight: 600; color: #e53e3e;">{{ $reason }}</td>
            </tr>
        </tbody>
    </table>

    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 20px; border: 1px dashed #cbd5e0;">
        <p style="margin: 0; font-size: 13px; color: #4a5568; line-height: 1.5; text-align: center;">
            <strong>Note:</strong> Data integrity remains intact. To remove this ingredient, you must first delete or update any <strong>Recipes</strong> that list it as a requirement.
        </p>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ url('/admin/ingredients') }}" class="button" style="background-color: #2d3748;">
            Review Ingredients
        </a>
    </div>
@endsection
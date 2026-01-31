@extends('mail.layout.mail')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="display: inline-block; background-color: #fff5f5; padding: 15px; border-radius: 50%; margin-bottom: 15px; border: 2px solid #feb2b2;">
            <span style="font-size: 30px;">🗑️</span>
        </div>
        <h2 style="color: #c53030; font-size: 24px; margin: 0;">Recipe Removed</h2>
        <p style="color: #718096;">A production formula has been permanently deleted from the system.</p>
    </div>

    <div class="alert-box" style="background-color: #fff5f5; border-left: 4px solid #e53e3e;">
        <p style="margin: 0; color: #9b2c2c; font-size: 14px; font-weight: 600;">
            ⚠️ Production Capability Lost
        </p>
        <p style="margin: 5px 0 0; font-size: 13px; color: #c53030;">
            The system can no longer calculate ingredient requirements for this product. <strong>Production runs are now disabled</strong> for this item until a new recipe is assigned.
        </p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th colspan="2">Deletion Audit</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #718096; width: 40%;">Recipe Name</td>
                <td style="font-weight: 700; color: #2d3748;">{{ $recipeName }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">Action Performed By</td>
                <td style="font-weight: 600; color: #2d3748;">{{ $user->name }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">User Account</td>
                <td style="font-size: 13px; color: #4a5568;">{{ $user->email }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">Timestamp</td>
                <td style="font-size: 13px; color: #a0aec0;">{{ now()->format('d M, Y | h:i A') }}</td>
            </tr>
        </tbody>
    </table>

    <div style="background-color: #fef2f2; padding: 20px; border-radius: 8px; margin-top: 20px; border: 1px solid #fee2e2; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #9b2c2c; line-height: 1.5;">
            <strong>Immediate Action:</strong> If this was an error, you must manually recreate the ingredient ratios to resume production for this product.
        </p>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ url('/admin/recipes') }}" class="button" style="background-color: #2d3748;">
            Rebuild Recipe
        </a>
    </div>
@endsection
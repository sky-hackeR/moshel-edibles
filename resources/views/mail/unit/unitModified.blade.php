@extends('mail.layout.mail')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="display: inline-block; background-color: #faf5ff; padding: 15px; border-radius: 50%; margin-bottom: 15px; border: 2px solid #e9d8fd;">
            <span style="font-size: 30px;">⚖️</span>
        </div>
        <h2 style="color: #6b46c1; font-size: 24px; margin: 0;">Unit Configuration {{ ucfirst($action) }}</h2>
        <p style="color: #718096;">A fundamental measurement unit has been modified in the system registry.</p>
    </div>

    <div class="alert-box" style="background-color: #fff5f5; border-left: 4px solid #e53e3e;">
        <p style="margin: 0; color: #9b2c2c; font-size: 14px; font-weight: 600;">
            🛑 Critical Calculation Warning
        </p>
        <p style="margin: 5px 0 0; font-size: 13px; color: #c53030;">
            Changes to <strong>multipliers</strong> or <strong>base units</strong> automatically recalculate all active recipes and current stock-on-hand. Verification is mandatory.
        </p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th colspan="2">Technical Specifications</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #718096; width: 40%;">Unit Name</td>
                <td style="font-weight: 700; color: #2d3748;">{{ $unit->name }} ({{ $unit->symbol }})</td>
            </tr>
            <tr>
                <td style="color: #718096;">Classification</td>
                <td style="font-weight: 600; color: #4a5568;">{{ ucfirst($unit->unit_type) }}</td>
            </tr>
            <tr>
                <td style="color: #718096;">Base Multiplier</td>
                <td style="font-family: 'Courier New', monospace; font-weight: 800; color: #6b46c1;">
                    × {{ $unit->base_multiplier }}
                </td>
            </tr>
            <tr>
                <td style="color: #718096;">Registry Status</td>
                <td>
                    <span style="padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; background-color: {{ $unit->is_active ? '#d1fae5' : '#fee2e2' }}; color: {{ $unit->is_active ? '#065f46' : '#991b1b' }};">
                        {{ $unit->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
            </tr>
        </tbody>
    </table>

    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 25px; border: 1px solid #edf2f7;">
        <p style="margin: 0; font-size: 13px; color: #4a5568;">
            <strong>Authorized By:</strong> {{ $user->name }} ({{ $user->email }})<br>
            <strong>Timestamp:</strong> {{ now()->format('d M, Y | h:i A') }}
        </p>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ url('/admin/unitManagement') }}" class="button" style="background-color: #6b46c1;">
            Review Unit Registry
        </a>
    </div>
@endsection
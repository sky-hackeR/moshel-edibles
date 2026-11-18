@extends('mail.layout.mail')

@section('preheader', '[UNIT CONFIGURATION] Unit ' . $unit->name . ' (' . $unit->symbol . ') was ' . $action)

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <span class="badge-purple" style="display: inline-block; margin-bottom: 10px;">Measurement Configuration</span>
        <h2 style="margin: 0 0 6px; font-size: 22px; color: #2a172e; font-weight: 700;">Unit Configuration {{ ucfirst($action) }}</h2>
        <p style="margin: 0; color: #6c5a70; font-size: 14px;">A fundamental measurement unit has been modified in the system registry.</p>
    </div>

    <div class="alert-box-warning" style="margin-bottom: 24px;">
        <p style="margin: 0 0 4px; color: #b45309; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Calculation Impact Notice</p>
        <p style="margin: 0; font-size: 13px; color: #92400e; line-height: 1.5;">
            Changes to <strong>multipliers</strong> or <strong>base units</strong> automatically recalculate active recipes and on-hand inventory balances.
        </p>
    </div>

    <table class="data-table" style="margin-bottom: 24px;">
        <thead>
            <tr>
                <th colspan="2" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.6px;">Technical Specifications</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: #6c5a70; width: 40%; font-weight: 500;">Unit Name</td>
                <td style="font-weight: 700; color: #2a172e; font-size: 15px;">{{ $unit->name }} ({{ $unit->symbol }})</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Classification</td>
                <td style="font-weight: 600; color: #581a62;">{{ ucfirst($unit->unit_type) }}</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Base Multiplier</td>
                <td style="font-family: 'Courier New', monospace; font-weight: 800; color: #581a62; font-size: 15px;">
                    × {{ $unit->base_multiplier }}
                </td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Registry Status</td>
                <td>
                    <span class="{{ $unit->is_active ? 'badge-success' : 'badge-danger' }}">
                        {{ $unit->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Authorized By</td>
                <td style="color: #2a172e; font-weight: 500;">{{ $user->name }} ({{ $user->email }})</td>
            </tr>
            <tr>
                <td style="color: #6c5a70; font-weight: 500;">Effective Timestamp</td>
                <td style="font-size: 13px; color: #6c5a70;">{{ now()->format('d M, Y | h:i A') }}</td>
            </tr>
        </tbody>
    </table>

    <div style="text-align: center;">
        <a href="{{ url('/admin/unitManagement') }}" class="button">
            Review Unit Registry
        </a>
    </div>
@endsection

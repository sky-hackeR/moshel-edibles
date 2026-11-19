@extends('staff.layout.dashboard')

@section('content')

@php
    $staff = Auth::guard('staff')->user();
    $fullName = $staff->name ?? 'Staff Member';
    $firstName = explode(' ', trim($fullName))[0] ?? 'Staff';
    $initials = '';
    $nameParts = preg_split('/\s+/u', trim($fullName), -1, PREG_SPLIT_NO_EMPTY);
    if (!empty($nameParts)) {
        $initials = mb_substr($nameParts[0], 0, 1, 'UTF-8');
        if (count($nameParts) > 1) {
            $initials .= mb_substr(end($nameParts), 0, 1, 'UTF-8');
        }
    }
    $salesContribution = $overallSalesCount > 0 ? round(($mySalesCount / $overallSalesCount) * 100) : 0;
@endphp

<div class="container-fluid py-3">

    {{-- 1. SHIFT WELCOME & FAST ACTION BANNER --}}
    @include('staff.partials.dashboard.shift-banner')

    {{-- 2. OPERATIONAL SHIFT KPI CARDS (4 TILES) --}}
    @include('staff.partials.dashboard.shift-kpi-cards')

    {{-- 3. FAST OPERATIONS DOCK (QUICK ACTION TILES) --}}
    @include('staff.partials.dashboard.quick-action-dock')

    {{-- 4. MASTER-DETAIL OPERATIONAL SECTION --}}
    <div class="row g-4">
        {{-- LEFT COLUMN: MY LIVE SHIFT ACTIVITY (col-xl-8) --}}
        @include('staff.partials.dashboard.shift-operations-log')

        {{-- RIGHT COLUMN: COUNTER READINESS & SHIFT INSIGHTS (col-xl-4) --}}
        @include('staff.partials.dashboard.counter-stock-and-alerts')
    </div>

</div>

<style>
    /* Staff Dashboard Styles */
    .staff-hero-avatar {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        color: #ffffff;
        font-weight: 800;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
    }
    .staff-pulse-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
        animation: staffPulse 1.8s infinite;
    }
    @keyframes staffPulse {
        0% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(1.3); }
        100% { opacity: 1; transform: scale(1); }
    }
    .staff-kpi-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .staff-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(27, 45, 57, 0.08) !important;
    }
    .staff-kpi-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .bg-emerald-subtle { background-color: #d1fae5; }
    .text-emerald { color: #059669; }
    .bg-indigo-subtle { background-color: #e0e7ff; }
    .text-indigo { color: #4338ca; }
    .bg-sky-subtle { background-color: #e0f2fe; }
    .text-sky { color: #0284c7; }
    .staff-action-tile {
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        border: 1px solid rgba(226, 232, 240, 0.9) !important;
    }
    .staff-action-tile:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.07) !important;
        border-color: #059669 !important;
    }
    .staff-activity-pills .nav-link {
        color: #64748b;
        background: #f1f5f9;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .staff-activity-pills .nav-link.active {
        color: #ffffff !important;
        background: #059669 !important;
        box-shadow: 0 2px 8px rgba(5, 150, 105, 0.35);
    }
    .staff-table thead th {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 0.75rem 1rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .staff-table tbody td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #f1f5f9;
    }
</style>

@endsection

@extends('staff.layout.dashboard')

@section('content')
    @include('shared.erp.sales.pos', [
        'portal' => 'staff',
        'guard' => 'staff',
        'cashierRole' => 'Staff',
        'cashierName' => Auth::guard('staff')->user()->name ?? 'Staff Member',
        'sellerLabel' => 'Staff',
        'salesHistoryUrl' => url('/staff/salesHistory'),
        'salesHistoryLabel' => 'My Sales Log',
        'processSaleEndpoint' => url('/staff/processSale'),
        'emptyTitle' => 'No products available',
        'emptyDescription' => 'Only items with positive stock are listed for cashier checkout.',
    ])
@endsection

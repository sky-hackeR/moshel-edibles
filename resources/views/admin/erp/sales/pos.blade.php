@extends('admin.layout.dashboard')

@section('content')
    @include('shared.erp.sales.pos', [
        'portal' => 'admin',
        'guard' => 'admin',
        'cashierRole' => 'Officer',
        'cashierName' => Auth::guard('admin')->user()->name ?? 'Administrator',
        'sellerLabel' => 'Admin',
        'salesHistoryUrl' => url('/admin/salesHistory'),
        'salesHistoryLabel' => 'Sales Log',
        'processSaleEndpoint' => url('/admin/processSale'),
        'emptyTitle' => 'No products configured',
        'emptyDescription' => 'Add active products in Product Management to begin selling.',
    ])
@endsection

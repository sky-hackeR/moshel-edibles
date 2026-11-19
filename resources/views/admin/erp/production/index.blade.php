@extends('admin.layout.dashboard')

@section('content')
    @include('shared.erp.production.index', [
        'portal' => 'admin',
        'products' => $products,
        'stats' => $stats,
        'productionMatrix' => $productionMatrix,
    ])
@endsection

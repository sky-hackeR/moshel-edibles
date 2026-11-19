@extends('admin.layout.dashboard')

@section('content')
    @include('shared.erp.inventory.index', [
        'portal' => 'admin',
        'inventories' => $inventories,
    ])
@endsection

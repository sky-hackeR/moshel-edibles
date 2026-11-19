@extends('staff.layout.dashboard')

@section('content')
    @include('shared.erp.inventory.index', [
        'portal' => 'staff',
        'inventories' => $inventories,
    ])
@endsection

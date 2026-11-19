@extends('staff.layout.dashboard')

@section('content')
    @include('shared.erp.users.profile', [
        'portal' => 'staff',
        'user' => $staff,
    ])
@endsection

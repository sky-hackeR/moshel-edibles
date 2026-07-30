@php

$status = strtolower($status);

@endphp

@if($status == 'success')

    <span class="badge bg-success">
        Success
    </span>

@elseif($status == 'failed')

    <span class="badge bg-danger">
        Failed
    </span>

@elseif($status == 'processing')

    <span class="badge bg-warning">
        Processing
    </span>

@elseif($status == 'partial')

    <span class="badge bg-info">
        Partial
    </span>

@elseif($status == 'active')

    <span class="badge bg-success">
        Active
    </span>

@elseif($status == 'inactive')

    <span class="badge bg-secondary">
        Inactive
    </span>

@else

    <span class="badge bg-dark">
        {{ ucfirst($status) }}
    </span>

@endif
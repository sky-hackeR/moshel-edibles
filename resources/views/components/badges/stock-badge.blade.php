@props([
    'quantity' => 0,
    'unit' => 'units',
    'threshold' => 10,
    'pill' => true,
])

@php
    $qty = (float) $quantity;
    $thresh = (float) $threshold;
    $pillClass = $pill ? 'rounded-pill px-2.5 py-1' : 'px-2 py-1';
@endphp

@if($qty > $thresh)
    <span class="badge bg-soft-success text-success font-size-12 {{ $pillClass }}">
        <i class="bx bx-check-circle me-1"></i> {{ $qty }} {{ $unit }}
    </span>
@elseif($qty > 0)
    <span class="badge bg-soft-warning text-warning font-size-12 {{ $pillClass }}">
        <i class="bx bx-error me-1"></i> {{ $qty }} {{ $unit }} (Low)
    </span>
@else
    <span class="badge bg-soft-secondary text-muted font-size-11 {{ $pillClass }}">
        0 {{ $unit }} (Depleted)
    </span>
@endif

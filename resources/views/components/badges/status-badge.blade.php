@props([
    'active' => true,
    'activeText' => 'Active',
    'disabledText' => 'Disabled',
    'pill' => false,
])

@if($active)
    <span class="badge bg-soft-success text-success font-size-11 {{ $pill ? 'rounded-pill px-2.5 py-1' : '' }}">
        <i class="bx bxs-circle font-size-8 me-1 align-middle"></i>{{ $activeText }}
    </span>
@else
    <span class="badge bg-soft-danger text-danger font-size-11 {{ $pill ? 'rounded-pill px-2.5 py-1' : '' }}">
        <i class="bx bxs-circle font-size-8 me-1 align-middle"></i>{{ $disabledText }}
    </span>
@endif

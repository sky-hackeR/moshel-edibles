@props([
    'label',
    'value',
    'icon' => null,
    'color' => 'primary',
    'borderColor' => null,
    'subtitle' => null,
    'subicon' => null,
    'subcolor' => null,
    'cardClass' => '',
])

@php
    $resolvedBorder = $borderColor ?: match($color) {
        'success' => '#059669',
        'info', 'cyan' => '#0284c7',
        'primary', 'indigo' => '#4f46e5',
        'warning', 'amber' => '#f59e0b',
        'danger', 'red' => '#dc2626',
        'purple' => '#7b2284',
        default => '#4f46e5'
    };

    $resolvedSubColor = $subcolor ?: match($color) {
        'success' => 'text-success',
        'info', 'cyan' => 'text-info',
        'primary', 'indigo' => 'text-primary',
        'warning', 'amber' => 'text-warning',
        'danger', 'red' => 'text-danger',
        'purple' => 'text-purple',
        default => 'text-muted'
    };
@endphp

<div class="card border-0 shadow-sm h-100 erp-kpi-card {{ $cardClass }}" style="border-left: 4px solid {{ $resolvedBorder }} !important;">
    <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between">
            <div style="min-width: 0;">
                <p class="text-muted text-uppercase font-size-11 fw-bold mb-1 text-truncate">{{ $label }}</p>
                <h4 class="mb-0 fw-bold text-dark text-truncate" title="{{ $value }}">{{ $value }}</h4>
                @if($subtitle)
                    <small class="{{ $resolvedSubColor }} font-size-11 fw-semibold d-block text-truncate mt-1">
                        @if($subicon)<i class="{{ $subicon }} me-1"></i>@endif{{ $subtitle }}
                    </small>
                @elseif(isset($slot) && $slot->isNotEmpty())
                    <div class="mt-1 font-size-11">{{ $slot }}</div>
                @endif
            </div>
            @if($icon)
                <div class="avatar-sm flex-shrink-0 ms-2">
                    <span class="avatar-title rounded-circle font-size-20 bg-{{ $color }}-subtle text-{{ $color }}">
                        <i class="{{ $icon }}"></i>
                    </span>
                </div>
            @endif
        </div>
    </div>
</div>

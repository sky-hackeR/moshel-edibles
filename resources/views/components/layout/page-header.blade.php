@props([
    'title',
    'subtitle' => null,
    'breadcrumb' => null,
    'icon' => null,
    'iconColor' => 'primary',
    'isDanger' => false,
])

@php
    $activeBreadcrumb = $breadcrumb ?: $subtitle ?: $title;
@endphp

<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between pb-2">
            <div class="d-flex align-items-center">
                @if($icon)
                    <div class="{{ $isDanger ? 'danger-header-badge' : 'avatar-sm' }} me-2 shadow-sm flex-shrink-0">
                        @if($isDanger)
                            <i class="{{ $icon }}"></i>
                        @else
                            <span class="avatar-title rounded-circle font-size-18 bg-{{ $iconColor }}-subtle text-{{ $iconColor }}">
                                <i class="{{ $icon }}"></i>
                            </span>
                        @endif
                    </div>
                @endif
                <div>
                    <h4 class="mb-0 font-size-18 fw-bold {{ $isDanger ? 'text-danger' : 'text-dark' }}">{{ $title }}</h4>
                    @if($subtitle)
                        <small class="text-muted font-size-12">{{ $subtitle }}</small>
                    @endif
                </div>
            </div>
            
            <div class="d-flex align-items-center flex-wrap gap-2 mt-2 mt-sm-0">
                @if(isset($slot) && $slot->isNotEmpty())
                    {{ $slot }}
                @endif
            </div>
        </div>
    </div>
</div>

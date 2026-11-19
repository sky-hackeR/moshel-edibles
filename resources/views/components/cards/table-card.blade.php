@props([
    'title',
    'icon' => null,
    'iconColor' => 'text-primary',
    'description' => null,
    'count' => null,
    'countLabel' => null,
    'cardClass' => '',
])

<div class="card border-0 shadow-sm mb-4 erp-directory-card {{ $cardClass }}" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white border-bottom py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h5 class="card-title mb-0 fw-bold text-dark">
                @if($icon)<i class="{{ $icon }} {{ $iconColor }} me-1"></i>@endif{{ $title }}
            </h5>
            @if($description)
                <small class="text-muted">{{ $description }}</small>
            @endif
        </div>
        <div class="d-flex align-items-center gap-2">
            @if(isset($headerActions) && $headerActions->isNotEmpty())
                {{ $headerActions }}
            @endif
            @if($count !== null)
                <span class="badge rounded-pill bg-light text-dark border px-3 py-1 font-size-11">
                    {{ is_numeric($count) ? number_format($count) : $count }} {{ $countLabel ?: '' }}
                </span>
            @endif
        </div>
    </div>

    <div class="card-body p-3">
        <div class="table-responsive">
            {{ $slot }}
        </div>
    </div>
</div>

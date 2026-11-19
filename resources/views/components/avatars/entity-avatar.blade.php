@props([
    'name',
    'image' => null,
    'unitType' => null,
    'size' => 36,
    'avatarClass' => '',
])

@php
    $initial = strtoupper(substr(trim($name ?: 'A'), 0, 1));
    
    // Automatic unit type or generic color mapping
    $bgStyle = match(strtolower($unitType ?? '')) {
        'mass' => 'background: #fef3c7; color: #b45309;',
        'volume' => 'background: #e0f2fe; color: #0369a1;',
        'count' => 'background: #dcfce7; color: #15803d;',
        'danger', 'red' => 'background: #fee2e2; color: #b91c1c;',
        'purple' => 'background: #f3e8ff; color: #7e22ce;',
        default => 'background: #eef2ff; color: #4338ca;'
    };
@endphp

@if($image)
    <img src="{{ asset($image) }}" alt="{{ $name }}" class="rounded me-2 object-fit-cover shadow-sm {{ $avatarClass }}" style="width: {{ $size }}px; height: {{ $size }}px;">
@else
    <div class="erp-icon-avatar me-2 shadow-sm {{ $avatarClass }}" style="{{ $bgStyle }}; width: {{ $size }}px; height: {{ $size }}px;">
        {{ $initial }}
    </div>
@endif

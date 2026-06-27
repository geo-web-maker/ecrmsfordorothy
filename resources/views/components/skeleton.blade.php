@props([
    'type' => 'line',
    'class' => '',
])

@php
    $base = match ($type) {
        'title' => 'skeleton skeleton-line skeleton-line--title',
        'card' => 'skeleton skeleton-card',
        'chart' => 'skeleton skeleton-chart',
        'map' => 'skeleton skeleton-map',
        'short' => 'skeleton skeleton-line skeleton-line--short',
        default => 'skeleton skeleton-line',
    };
@endphp

<div {{ $attributes->merge(['class' => $base.' '.$class, 'aria-hidden' => 'true']) }}></div>

@props([
    'src',
    'alt' => '',
    'class' => '',
    'wrapperClass' => '',
    'priority' => false,
    'height' => 'auto',
])

<div {{ $attributes->merge(['class' => 'lazy-media '.$wrapperClass]) }} data-lazy-media style="{{ $height !== 'auto' ? "min-height: {$height};" : '' }}">
    <div class="lazy-media__skeleton skeleton skeleton-shimmer" aria-hidden="true"></div>
    <img
        src="{{ $src }}"
        alt="{{ $alt }}"
        @class(['lazy-media__img', $class])
        @if ($priority)
            loading="eager"
            fetchpriority="high"
            decoding="sync"
        @else
            loading="lazy"
            decoding="async"
        @endif
    >
</div>

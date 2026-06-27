@php
    $variant = $variant ?? 'light';
    $systemFullName = 'Environmental Crime Reporting & Monitoring System';
    $titleColor = $variant === 'light' ? '#DDE8C8' : '#3F6B2A';
    $subtitleColor = $variant === 'light' ? 'rgba(221,232,200,0.75)' : '#7B8F69';
    $dotColor = $variant === 'light' ? '#DDE8C8' : '#3F6B2A';
@endphp

<div class="flex items-start gap-2.5 {{ $class ?? '' }}">
    <div style="width:10px; height:10px; border-radius:50%; background:{{ $dotColor }}; margin-top:6px; flex-shrink:0;"></div>
    <div class="min-w-0">
        <p style="font-size:11px; font-weight:800; color:{{ $titleColor }}; letter-spacing:2px; text-transform:uppercase; margin:0; line-height:1.3;">ECRMS</p>
        <p style="font-size:10px; font-weight:600; color:{{ $subtitleColor }}; margin:4px 0 0; line-height:1.5; max-width:280px;">{{ $systemFullName }}</p>
    </div>
</div>

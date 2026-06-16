@php
    $colors = match ($status) {
        'Submitted' => 'bg-blue-100 text-blue-800',
        'Under Review' => 'bg-yellow-100 text-yellow-800',
        'Assigned' => 'bg-purple-100 text-purple-800',
        'Resolved' => 'bg-emerald-100 text-emerald-800',
        'Closed' => 'bg-gray-100 text-gray-800',
        default => 'bg-gray-100 text-gray-700',
    };
@endphp
<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $colors }}">
    {{ $status }}
</span>

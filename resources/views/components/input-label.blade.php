@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-xs uppercase tracking-wider text-[#3F6B2A] mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>

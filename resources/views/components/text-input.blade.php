@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-2 border-[#D8DECB] rounded-xl px-4 py-2.5 text-sm text-[#1F3318] placeholder-[#7B8F69] bg-white transition-all focus:border-[#5E8B3D] focus:ring focus:ring-[#5E8B3D]/15 focus:outline-none shadow-sm block w-full']) }}>

<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-white border-2 border-[#D8DECB] rounded-xl font-bold text-sm text-[#5F6B57] hover:text-[#3F6B2A] hover:bg-[#F6F8F1] hover:border-[#3F6B2A]/40 transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#5E8B3D]/30 shadow-sm disabled:opacity-50']) }}>
    {{ $slot }}
</button>

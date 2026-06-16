<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-[#c0392b] border-none rounded-xl font-bold text-sm text-white hover:bg-[#a63022] active:bg-[#8e281b] focus:outline-none focus:ring-2 focus:ring-[#c0392b] focus:ring-offset-2 transition-all shadow-md hover:shadow-lg cursor-pointer']) }}>
    {{ $slot }}
</button>

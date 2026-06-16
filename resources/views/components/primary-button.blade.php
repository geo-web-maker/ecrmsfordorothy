<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 rounded-xl font-bold text-sm text-white bg-[#3F6B2A] border-none hover:bg-[#2C4424] active:bg-[#1F3318] focus:outline-none focus:ring-2 focus:ring-[#3F6B2A] focus:ring-offset-2 transition-all shadow-md hover:shadow-lg cursor-pointer']) }}>
    {{ $slot }}
</button>

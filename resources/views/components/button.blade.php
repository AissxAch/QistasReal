<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-[#1c5bb8] border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-[#153e84] active:bg-[#0f2d62] focus:outline-none focus:ring-2 focus:ring-[#1c5bb8]/40 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-white border border-slate-300 rounded-xl font-bold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 hover:border-[#1c5bb8] hover:text-[#1c5bb8] focus:outline-none focus:ring-2 focus:ring-[#1c5bb8]/30 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

<div class="min-h-screen flex flex-col justify-center items-center px-4 py-10">
    <div class="mb-6">
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md px-6 py-6 bg-white/95 border border-slate-200 shadow-xl overflow-hidden rounded-2xl">
        {{ $slot }}
    </div>
</div>

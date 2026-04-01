<x-guest-layout>
    <div class="pt-4">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
            <div>
                <x-authentication-card-logo />
            </div>

            <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white/95 border border-slate-200 shadow-xl overflow-hidden rounded-2xl prose prose-slate">
                {!! $policy !!}
            </div>
        </div>
    </div>
</x-guest-layout>

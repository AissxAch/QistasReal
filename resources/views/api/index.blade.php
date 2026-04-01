<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('API Tokens') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-4 sm:p-6">
                @livewire('api.api-token-manager')
            </div>
        </div>
    </div>
</x-app-layout>

@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#1c5bb8] text-sm font-semibold leading-5 text-[#153e84] focus:outline-none focus:border-[#153e84] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-slate-500 hover:text-[#1c5bb8] hover:border-slate-300 focus:outline-none focus:text-[#1c5bb8] focus:border-slate-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-slate-300 bg-white focus:border-[#1c5bb8] focus:ring-[#1c5bb8]/30 rounded-xl shadow-sm']) !!}>

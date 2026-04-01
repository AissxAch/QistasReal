@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'mt-2 text-sm font-medium text-red-700']) }}>{{ $message }}</p>
@enderror

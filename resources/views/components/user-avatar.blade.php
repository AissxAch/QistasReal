@props(['user', 'size' => 'md', 'showName' => false])

@php
    $sizeClasses = match($size) {
        'xs' => 'w-6 h-6 text-xs',
        'sm' => 'w-8 h-8 text-xs',
        'md' => 'w-10 h-10 text-sm',
        'lg' => 'w-12 h-12 text-base',
        'xl' => 'w-16 h-16 text-lg',
        default => 'w-10 h-10 text-sm',
    };

    $initials = substr($user->name, 0, 1);
    $hasProfilePhoto = $user->profile_photo_path && file_exists(storage_path('app/public/' . $user->profile_photo_path));
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
    @if($hasProfilePhoto)
        <img src="{{ Storage::url($user->profile_photo_path) }}"
             alt="{{ $user->name }}"
             class="{{ $sizeClasses }} rounded-lg object-cover shadow-sm border border-gray-200"
        />
    @else
        <div class="bg-gradient-to-br from-blue-400 to-blue-600 {{ $sizeClasses }} rounded-lg flex items-center justify-center text-white font-bold shadow-sm border border-blue-200">
            {{ $initials }}
        </div>
    @endif

    @if($showName)
        <div class="min-w-0">
            <p class="text-xs font-bold text-gray-800 truncate">{{ $user->name }}</p>
            <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
        </div>
    @endif
</div>

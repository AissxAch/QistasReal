@extends('layouts.app')

@section('title', 'إعدادات الحساب')

@section('content')
<div x-data="{ showSuccess: true }" x-init="setTimeout(() => showSuccess = false, 4000)">
    {{-- Success flash message --}}
    @if(session('status'))
        <div x-show="showSuccess" x-transition.duration.300ms class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4">
            <i class="fas fa-check-circle ml-2"></i> {{ session('status') }}
        </div>
    @endif

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4">
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-exclamation-triangle"></i>
                <strong class="font-semibold">حدثت أخطاء في الإدخال</strong>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="space-y-8">
        {{-- Update Profile Information --}}
        @if (Laravel\Fortify\Features::canUpdateProfileInformation())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                    <i class="fas fa-user-edit text-[#1c5bb8]"></i>
                    تحديث معلومات الملف الشخصي
                </h2>
                @livewire('profile.update-profile-information-form')
            </div>
        @endif

        {{-- Update Password --}}
        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                    <i class="fas fa-key text-[#1c5bb8]"></i>
                    تغيير كلمة المرور
                </h2>
                @livewire('profile.update-password-form')
            </div>
        @endif

        {{-- Two Factor Authentication --}}
        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                    <i class="fas fa-shield-alt text-[#1c5bb8]"></i>
                    المصادقة الثنائية
                </h2>
                @livewire('profile.two-factor-authentication-form')
            </div>
        @endif

        {{-- Browser Sessions --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                <i class="fas fa-desktop text-[#1c5bb8]"></i>
                جلسات المتصفح
            </h2>
            @livewire('profile.logout-other-browser-sessions-form')
        </div>

        {{-- Delete Account --}}
        @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
            <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6">
                <h2 class="text-xl font-bold text-red-800 mb-5 flex items-center gap-2 border-b border-red-100 pb-3">
                    <i class="fas fa-trash-alt text-red-600"></i>
                    حذف الحساب
                </h2>
                @livewire('profile.delete-user-form')
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'الإعدادات')

@section('content')
<div x-data="{ tab: 'profile', showSuccess: true }" x-init="setTimeout(() => showSuccess = false, 4000)">
    {{-- Success flash message --}}
    @if(session('success'))
        <div x-show="showSuccess" x-transition.duration.300ms class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4">
            <i class="fas fa-check-circle ml-2"></i> {{ session('success') }}
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

    {{-- Tabs --}}
    <div class="mb-8">
        <nav class="flex gap-4" aria-label="Tabs">
            @if(auth()->user()->isOwner())
                <button @click="tab = 'firm'" 
                    :class="tab === 'firm' ? 'bg-[#1c5bb8] text-white' : 'text-gray-500 hover:text-gray-700'"
                    class="rounded-xl px-4 py-2 font-medium text-sm transition">
                    إعدادات المكتب
                </button>
            @endif
            <button @click="tab = 'profile'"
                :class="tab === 'profile' ? 'bg-[#1c5bb8] text-white' : 'text-gray-500 hover:text-gray-700'"
                class="rounded-xl px-4 py-2 font-medium text-sm transition">
                الملف الشخصي
            </button>
        </nav>
    </div>

    {{-- TAB 1: Firm Settings --}}
    @if(auth()->user()->isOwner())
        <div x-show="tab === 'firm'" x-cloak>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                    <i class="fas fa-building text-[#1c5bb8]"></i>
                    إعدادات المكتب
                </h2>
                <form action="{{ route('settings.firm') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">اسم المكتب <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $lawFirm->name) }}" required
                                class="w-full px-3 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email', $lawFirm->email) }}"
                                class="w-full px-3 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">رقم الهاتف</label>
                            <input type="text" name="phone" value="{{ old('phone', $lawFirm->phone) }}"
                                class="w-full px-3 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">الرقم الضريبي</label>
                            <input type="text" name="tax_number" value="{{ old('tax_number', $lawFirm->tax_number) }}"
                                class="w-full px-3 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
                            @error('tax_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">العنوان</label>
                        <textarea name="address" rows="3"
                            class="w-full px-3 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">{{ old('address', $lawFirm->address) }}</textarea>
                        @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">شعار المكتب</label>
                        @if($lawFirm->logo)
                            <div class="mb-2">
                                <img src="{{ Storage::url($lawFirm->logo) }}" class="h-20 w-auto rounded-lg border border-gray-200">
                            </div>
                        @endif
                        <input type="file" name="logo" accept="image/*"
                            class="w-full px-3 py-2 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-[#1c5bb8] file:text-white hover:file:bg-[#0f2d62]">
                        @error('logo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-[#1c5bb8] text-white rounded-xl px-6 py-2.5 hover:bg-[#0f2d62] transition shadow-sm flex items-center gap-2">
                            <i class="fas fa-save"></i> حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div x-show="tab === 'firm'" x-cloak class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 text-center">
            <i class="fas fa-lock text-yellow-600 text-3xl mb-2 block"></i>
            <p class="text-yellow-800">هذا القسم متاح لمالك المكتب فقط.</p>
        </div>
    @endif

    {{-- TAB 2: Profile Settings --}}
    <div x-show="tab === 'profile'" x-cloak>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                <i class="fas fa-user-circle text-[#1c5bb8]"></i>
                الملف الشخصي
            </h2>
            <form action="{{ route('settings.profile') }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">الاسم الكامل <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-3 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">البريد الإلكتروني</label>
                        <input type="email" value="{{ $user->email }}" readonly disabled
                            class="w-full px-3 py-3.5 rounded-2xl border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">رقم الهاتف</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                            class="w-full px-3 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">التخصص</label>
                        <input type="text" name="specialty" value="{{ old('specialty', $user->specialty ?? '') }}"
                            class="w-full px-3 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
                        @error('specialty') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">نبذة شخصية</label>
                    <textarea name="bio" rows="3"
                        class="w-full px-3 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">{{ old('bio', $user->bio ?? '') }}</textarea>
                    @error('bio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-[#1c5bb8] text-white rounded-xl px-6 py-2.5 hover:bg-[#0f2d62] transition shadow-sm flex items-center gap-2">
                        <i class="fas fa-save"></i> حفظ التغييرات
                    </button>
                </div>
            </form>
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">
                    لتغيير كلمة المرور
                    <a href="{{ route('profile.show') }}" class="text-[#1c5bb8] text-sm hover:underline">
                        إعدادات الحساب المتقدمة
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
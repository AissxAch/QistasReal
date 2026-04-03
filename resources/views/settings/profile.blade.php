@extends('layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
<div x-data="{ showSuccess: true }" x-init="setTimeout(() => showSuccess = false, 4000)">

    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">الملف الشخصي</h1>
            <p class="text-sm text-gray-500 mt-0.5">تحديث معلوماتك الشخصية وصورتك</p>
        </div>
        @if(auth()->user()->isOwner())
            <a href="{{ route('settings.firm') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                <i class="fas fa-building"></i>
                إعدادات المكتب
            </a>
        @endif
    </div>

    {{-- Flash success --}}
    @if(session('success'))
        <div x-show="showSuccess" x-transition.duration.300ms
             class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4 flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
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

    {{-- Avatar card --}}
    <div x-data="{
            preview: '{{ $user->profile_photo_url }}',
            changed: false,
            pick(e) {
                const file = e.target.files[0];
                if (!file) return;
                this.preview = URL.createObjectURL(file);
                this.changed = true;
            }
        }"
         class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">

        <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
            <i class="fas fa-camera text-[#1c5bb8]"></i>
            الصورة الشخصية
        </h2>

        <form action="{{ route('settings.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="name" value="{{ $user->name }}">
            <input type="hidden" name="phone" value="{{ $user->phone ?? '' }}">
            <input type="hidden" name="specialty" value="{{ $user->specialty ?? '' }}">
            <input type="hidden" name="bio" value="{{ $user->bio ?? '' }}">

            <div class="flex items-center gap-6">
                <div class="relative group cursor-pointer flex-shrink-0" @click="$refs.avatarInput.click()">
                    <img :src="preview" alt="صورتك الشخصية"
                         class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md ring-2 ring-gray-100">
                    <div class="absolute inset-0 rounded-full bg-black/50 opacity-0 group-hover:opacity-100 transition flex flex-col items-center justify-center gap-1">
                        <i class="fas fa-camera text-white text-lg"></i>
                        <span class="text-white text-[10px] font-medium">تغيير</span>
                    </div>
                </div>

                <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp"
                       x-ref="avatarInput" @change="pick($event)" class="hidden">

                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-700 mb-1">{{ $user->name }}</p>
                    <p class="text-xs text-gray-400 mb-3">JPG أو PNG أو WebP — حجم أقصى 2 ميجابايت</p>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" @click="$refs.avatarInput.click()"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg border border-[#1c5bb8] text-[#1c5bb8] hover:bg-[#1c5bb8] hover:text-white transition">
                            <i class="fas fa-upload text-xs"></i> رفع صورة
                        </button>
                        <button type="submit" x-show="changed" x-cloak
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg bg-[#1c5bb8] text-white hover:bg-[#0f2d62] transition">
                            <i class="fas fa-save text-xs"></i> حفظ الصورة
                        </button>
                    </div>
                </div>
            </div>
        </form>

        @if($user->profile_photo_path)
            <form action="{{ route('settings.avatar.remove') }}" method="POST" class="mt-4">
                @csrf
                @method('DELETE')
                <button type="submit"
                        onclick="return confirm('هل تريد حذف صورتك الشخصية؟')"
                        class="text-xs text-red-500 hover:text-red-700 flex items-center gap-1.5 transition">
                    <i class="fas fa-trash-alt"></i> حذف الصورة الحالية
                </button>
            </form>
        @endif
    </div>

    {{-- Profile info form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
            <i class="fas fa-user-circle text-[#1c5bb8]"></i>
            المعلومات الشخصية
        </h2>
        <form action="{{ route('settings.profile.update') }}" method="POST" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">الاسم الكامل <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">البريد الإلكتروني</label>
                    <input type="email" value="{{ $user->email }}" readonly disabled
                        class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed">
                    <p class="text-xs text-gray-400 mt-1">لا يمكن تغيير البريد الإلكتروني.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">رقم الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                        class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">التخصص</label>
                    <input type="text" name="specialty" value="{{ old('specialty', $user->specialty ?? '') }}"
                        class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
                    @error('specialty') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">نبذة شخصية</label>
                <textarea name="bio" rows="3"
                    class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">{{ old('bio', $user->bio ?? '') }}</textarea>
                @error('bio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('profile.show') }}"
                   class="text-sm text-[#1c5bb8] hover:underline flex items-center gap-1.5">
                    <i class="fas fa-lock text-xs"></i>
                    تغيير كلمة المرور
                </a>
                <button type="submit"
                    class="bg-[#1c5bb8] text-white rounded-xl px-6 py-2.5 hover:bg-[#0f2d62] transition shadow-sm flex items-center gap-2">
                    <i class="fas fa-save"></i> حفظ التغييرات
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

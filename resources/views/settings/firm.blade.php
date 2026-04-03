@extends('layouts.app')

@section('title', 'إعدادات المكتب')

@section('content')
<div x-data="{ showSuccess: true }" x-init="setTimeout(() => showSuccess = false, 4000)">

    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">إعدادات المكتب</h1>
            <p class="text-sm text-gray-500 mt-0.5">تحديث بيانات مكتبك القانوني وشعاره</p>
        </div>
        <a href="{{ route('settings.profile') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
            <i class="fas fa-user-circle"></i>
            الملف الشخصي
        </a>
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

    @if(!auth()->user()->isOwner())
        {{-- Not owner — access denied --}}
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-8 text-center">
            <i class="fas fa-lock text-yellow-500 text-4xl mb-3 block"></i>
            <h3 class="text-lg font-semibold text-yellow-800 mb-1">غير مصرح بالوصول</h3>
            <p class="text-yellow-700 text-sm">هذه الصفحة متاحة لمالك المكتب فقط.</p>
        </div>
    @else

        {{-- ─── Logo card ──────────────────────────────────── --}}
        <div x-data="{
                preview: '{{ $lawFirm->logo ? Storage::url($lawFirm->logo) : '' }}',
                hasLogo: {{ $lawFirm->logo ? 'true' : 'false' }},
                changed: false,
                pick(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    this.preview = URL.createObjectURL(file);
                    this.hasLogo = true;
                    this.changed = true;
                }
            }"
             class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">

            <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                <i class="fas fa-image text-[#1c5bb8]"></i>
                شعار المكتب
            </h2>

            <form action="{{ route('settings.firm.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- Pass current info fields so nothing gets blanked --}}
                <input type="hidden" name="name"       value="{{ $lawFirm->name }}">
                <input type="hidden" name="email"      value="{{ $lawFirm->email ?? '' }}">
                <input type="hidden" name="phone"      value="{{ $lawFirm->phone ?? '' }}">
                <input type="hidden" name="address"    value="{{ $lawFirm->address ?? '' }}">
                <input type="hidden" name="tax_number" value="{{ $lawFirm->tax_number ?? '' }}">

                <div class="flex items-center gap-6">
                    {{-- Logo display / picker --}}
                    <div class="relative group cursor-pointer flex-shrink-0" @click="$refs.logoInput.click()">
                        <template x-if="hasLogo">
                            <img :src="preview" alt="شعار المكتب"
                                 class="h-24 w-44 object-contain rounded-xl border border-gray-200 bg-gray-50 shadow-sm">
                        </template>
                        <template x-if="!hasLogo">
                            <div class="h-24 w-44 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex flex-col items-center justify-center gap-1 text-gray-400">
                                <i class="fas fa-image text-2xl"></i>
                                <span class="text-xs">لا يوجد شعار</span>
                            </div>
                        </template>
                        <div class="absolute inset-0 rounded-xl bg-black/50 opacity-0 group-hover:opacity-100 transition flex flex-col items-center justify-center gap-1">
                            <i class="fas fa-camera text-white text-xl"></i>
                            <span class="text-white text-xs font-medium">تغيير الشعار</span>
                        </div>
                    </div>

                    <input type="file" name="logo" accept="image/jpeg,image/png"
                           x-ref="logoInput" @change="pick($event)" class="hidden">

                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-700 mb-1">{{ $lawFirm->name }}</p>
                        <p class="text-xs text-gray-400 mb-3">JPG أو PNG — حجم أقصى 2 ميجابايت — ينصح بصورة شفافة (PNG)</p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" @click="$refs.logoInput.click()"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg border border-[#1c5bb8] text-[#1c5bb8] hover:bg-[#1c5bb8] hover:text-white transition">
                                <i class="fas fa-upload text-xs"></i> رفع شعار
                            </button>
                            <button type="submit" x-show="changed" x-cloak
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg bg-[#1c5bb8] text-white hover:bg-[#0f2d62] transition">
                                <i class="fas fa-save text-xs"></i> حفظ الشعار
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            @if($lawFirm->logo)
                <form action="{{ route('settings.firm.logo.remove') }}" method="POST" class="mt-4">
                    @csrf @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('هل تريد حذف شعار المكتب؟')"
                            class="text-xs text-red-500 hover:text-red-700 flex items-center gap-1.5 transition">
                        <i class="fas fa-trash-alt"></i> حذف الشعار الحالي
                    </button>
                </form>
            @endif
        </div>

        {{-- ─── Firm info form ──────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                <i class="fas fa-building text-[#1c5bb8]"></i>
                بيانات المكتب
            </h2>
            <form action="{{ route('settings.firm.update') }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">اسم المكتب <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $lawFirm->name) }}" required
                            class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email', $lawFirm->email) }}"
                            class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">رقم الهاتف</label>
                        <input type="text" name="phone" value="{{ old('phone', $lawFirm->phone) }}"
                            class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">الرقم الضريبي</label>
                        <input type="text" name="tax_number" value="{{ old('tax_number', $lawFirm->tax_number) }}"
                            class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
                        @error('tax_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">العنوان</label>
                    <textarea name="address" rows="3"
                        class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">{{ old('address', $lawFirm->address) }}</textarea>
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit"
                        class="bg-[#1c5bb8] text-white rounded-xl px-6 py-2.5 hover:bg-[#0f2d62] transition shadow-sm flex items-center gap-2">
                        <i class="fas fa-save"></i> حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>

    @endif

</div>
@endsection

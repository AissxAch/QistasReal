@extends('layouts.app')

@section('title', 'إضافة عضو فريق')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">إضافة عضو فريق</h1>
        <p class="text-sm text-gray-500 mt-1">دعوة عضو جديد للانضمام إلى المكتب</p>
    </div>

    <form method="POST" action="{{ route('team.store') }}">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-lg font-bold text-gray-800">بيانات العضو</h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">الاسم <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('name') border-red-500 @enderror">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">البريد الإلكتروني <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('email') border-red-500 @enderror">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">الدور <span class="text-red-500">*</span></label>
                            <select name="role" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('role') border-red-500 @enderror">
                                <option value="lawyer" {{ old('role', 'lawyer') === 'lawyer' ? 'selected' : '' }}>محامي</option>
                                <option value="assistant" {{ old('role') === 'assistant' ? 'selected' : '' }}>مساعد</option>
                            </select>
                            @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">رقم الهاتف</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('phone') border-red-500 @enderror">
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">التخصص</label>
                            <input type="text" name="specialty" value="{{ old('specialty') }}" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('specialty') border-red-500 @enderror">
                            @error('specialty') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-envelope-open-text mt-0.5"></i>
                        <div>
                            <p class="font-semibold">سيتم إرسال دعوة تفعيل إلى البريد الإلكتروني مباشرة.</p>
                            <p class="mt-1 text-amber-700">صلاحية الدعوة 7 أيام، ويمكنك إعادة إرسالها لاحقاً من صفحة الفريق إذا لم يتم التفعيل.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="bg-gray-50/80 rounded-2xl shadow-md border border-gray-100 p-6 sticky top-24">
                    <h3 class="font-bold text-lg text-gray-800 mb-5">إجراءات</h3>
                    <div class="space-y-4">
                        <button type="submit" class="w-full py-3.5 rounded-xl bg-[#1c5bb8] text-white font-semibold hover:bg-[#0f2d62] transition shadow-sm flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i> حفظ العضو
                        </button>
                        <a href="{{ route('team.index') }}" class="w-full block text-center py-3.5 rounded-xl text-gray-500 hover:text-gray-700 transition">إلغاء</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

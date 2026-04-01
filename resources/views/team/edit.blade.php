@extends('layouts.app')

@section('title', 'تعديل عضو الفريق')

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
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">تعديل عضو الفريق</h1>
        <p class="text-sm text-gray-500 mt-1">تحديث بيانات العضو</p>
    </div>

    <form method="POST" action="{{ route('team.update', $member) }}">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-lg font-bold text-gray-800">بيانات العضو</h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">الاسم <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $member->name) }}" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('name') border-red-500 @enderror">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">البريد الإلكتروني</label>
                            <input type="email" value="{{ $member->email }}" disabled class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">الدور <span class="text-red-500">*</span></label>
                            <select name="role" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('role') border-red-500 @enderror">
                                <option value="owner" {{ old('role', $member->role) === 'owner' ? 'selected' : '' }}>المالك</option>
                                <option value="lawyer" {{ old('role', $member->role) === 'lawyer' ? 'selected' : '' }}>محامي</option>
                                <option value="assistant" {{ old('role', $member->role) === 'assistant' ? 'selected' : '' }}>مساعد</option>
                            </select>
                            @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">رقم الهاتف</label>
                            <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('phone') border-red-500 @enderror">
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">التخصص</label>
                            <input type="text" name="specialty" value="{{ old('specialty', $member->specialty) }}" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('specialty') border-red-500 @enderror">
                            @error('specialty') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="bg-gray-50/80 rounded-2xl shadow-md border border-gray-100 p-6 sticky top-24">
                    <h3 class="font-bold text-lg text-gray-800 mb-5">إجراءات</h3>
                    <div class="space-y-4">
                        <button type="submit" class="w-full py-3.5 rounded-xl bg-[#1c5bb8] text-white font-semibold hover:bg-[#0f2d62] transition shadow-sm flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i> حفظ التغييرات
                        </button>
                        <a href="{{ route('team.index') }}" class="w-full block text-center py-3.5 rounded-xl text-gray-500 hover:text-gray-700 transition">إلغاء</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

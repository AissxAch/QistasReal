@extends('layouts.app')

@section('title', 'تعديل الجلسة')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div>
        <nav class="flex mb-2 text-sm text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1c5bb8] transition">لوحة التحكم</a>
            <span class="mx-2">/</span>
            <a href="{{ route('sessions.index') }}" class="hover:text-[#1c5bb8] transition">الجلسات</a>
            <span class="mx-2">/</span>
            <a href="{{ route('sessions.show', $session) }}" class="hover:text-[#1c5bb8] transition">عرض</a>
            <span class="mx-2">/</span>
            <span class="text-gray-700">تعديل</span>
        </nav>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">تعديل الجلسة</h1>
        <p class="text-sm text-gray-500 mt-1">قم بتحديث بيانات الجلسة</p>
    </div>

    <form method="POST" action="{{ route('sessions.update', $session) }}">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-lg font-bold text-gray-800">بيانات الجلسة</h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">اختر القضية <span class="text-red-500">*</span></label>
                            <select name="case_id" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('case_id') border-red-500 @enderror">
                                <option value="">-- اختر القضية --</option>
                                @foreach($cases as $case)
                                    <option value="{{ $case->id }}" {{ (string) old('case_id', $session->case_id) === (string) $case->id ? 'selected' : '' }}>
                                        {{ $case->case_number }} - {{ $case->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('case_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">تاريخ الجلسة <span class="text-red-500">*</span></label>
                            <input type="date" name="session_date" value="{{ old('session_date', optional($session->session_date)->format('Y-m-d')) }}" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('session_date') border-red-500 @enderror">
                            @error('session_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">وقت الجلسة <span class="text-red-500">*</span></label>
                            <input type="time" name="session_time" value="{{ old('session_time', optional($session->session_time)->format('H:i')) }}" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('session_time') border-red-500 @enderror">
                            @error('session_time')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">اسم المحكمة <span class="text-red-500">*</span></label>
                            <input type="text" name="court" value="{{ old('court', $session->court) }}" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('court') border-red-500 @enderror">
                            @error('court')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">رقم القاعة</label>
                            <input type="text" name="room" value="{{ old('room', $session->room) }}" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('room') border-red-500 @enderror">
                            @error('room')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">الحالة</label>
                            <select name="status" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('status') border-red-500 @enderror">
                                <option value="scheduled" {{ old('status', $session->status) === 'scheduled' ? 'selected' : '' }}>مجدولة</option>
                                <option value="done" {{ old('status', $session->status) === 'done' ? 'selected' : '' }}>منعقدت</option>
                                <option value="postponed" {{ old('status', $session->status) === 'postponed' ? 'selected' : '' }}>مؤجلة</option>
                                <option value="cancelled" {{ old('status', $session->status) === 'cancelled' ? 'selected' : '' }}>ملغاة</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">ملاحظات</label>
                            <textarea name="notes" rows="4" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('notes') border-red-500 @enderror" placeholder="أضف أي ملاحظات تخص الجلسة...">{{ old('notes', $session->notes) }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="bg-gray-50/80 rounded-2xl shadow-md border border-gray-100 p-6 sticky top-24">
                    <h3 class="font-bold text-lg text-gray-800 mb-5">إجراءات</h3>
                    <div class="space-y-4">
                        <button type="submit" class="w-full py-3.5 rounded-xl bg-[#1c5bb8] text-white font-semibold hover:bg-[#0f2d62] transition shadow-sm flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i> تحديث الجلسة
                        </button>
                        <a href="{{ route('sessions.show', $session) }}" class="w-full block text-center py-3.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition">عرض الجلسة</a>
                        <a href="{{ route('sessions.index') }}" class="w-full block text-center py-3.5 rounded-xl text-gray-500 hover:text-gray-700 transition">إلغاء</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

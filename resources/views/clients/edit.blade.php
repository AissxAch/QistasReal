@extends('layouts.app')

@section('title', 'تعديل بيانات العميل')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <nav class="flex mb-2 text-sm text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1c5bb8] transition">لوحة التحكم</a>
            <span class="mx-2">/</span>
            <a href="{{ route('clients.index') }}" class="hover:text-[#1c5bb8] transition">العملاء</a>
            <span class="mx-2">/</span>
            <span class="text-gray-700">تعديل: {{ $client->name }}</span>
        </nav>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">تعديل بيانات العميل</h1>
        <p class="text-sm text-gray-500 mt-1">قم بتحديث المعلومات الخاصة بالعميل</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
        <form action="{{ route('clients.update', $client) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-5">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">الاسم <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $client->name) }}" required
                           class="w-full px-4 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200 @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone', $client->phone) }}" dir="ltr"
                           class="w-full px-4 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200 @error('phone') border-red-500 @enderror">
                    @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email', $client->email) }}" dir="ltr"
                           class="w-full px-4 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200 @error('email') border-red-500 @enderror">
                    @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">العنوان</label>
                    <input type="text" name="address" value="{{ old('address', $client->address) }}"
                           class="w-full px-4 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200 @error('address') border-red-500 @enderror">
                    @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ID Number -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">رقم الهوية</label>
                    <input type="text" name="id_number" value="{{ old('id_number', $client->id_number) }}" dir="ltr"
                           class="w-full px-4 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200 @error('id_number') border-red-500 @enderror">
                    @error('id_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">ملاحظات</label>
                    <textarea name="notes" rows="3"
                              class="w-full px-4 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200 @error('notes') border-red-500 @enderror">{{ old('notes', $client->notes) }}</textarea>
                    @error('notes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">إسناد العميل لمحامين</label>
                    @php($selectedLawyerIds = old('lawyer_ids', $client->lawyers->pluck('id')->toArray()))
                    <select name="lawyer_ids[]" multiple
                           class="w-full px-4 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200 min-h-36 @error('lawyer_ids') border-red-500 @enderror @error('lawyer_ids.*') border-red-500 @enderror">
                        @foreach(($lawyers ?? collect()) as $lawyer)
                            <option value="{{ $lawyer->id }}" {{ in_array($lawyer->id, $selectedLawyerIds) ? 'selected' : '' }}>{{ $lawyer->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">يمكنك اختيار أكثر من محامٍ.</p>
                    @error('lawyer_ids')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @error('lawyer_ids.*')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-4">
                    <button type="submit"
                            class="flex-1 py-3.5 rounded-xl bg-gradient-to-r from-[#1c5bb8] to-[#2a6dc9] text-white font-semibold hover:from-[#0f2d62] hover:to-[#1c5bb8] transition-all duration-200 shadow-md hover:shadow-lg">
                        تحديث البيانات
                    </button>
                    <a href="{{ route('clients.index') }}"
                       class="flex-1 py-3.5 rounded-xl border-2 border-gray-200 text-center text-gray-700 font-semibold hover:bg-gray-50 transition-all duration-200">
                        إلغاء
                    </a>
                </div>
            </div>
        </form>

        <!-- Delete Button (outside form) -->
        <div class="mt-6 pt-4 border-t border-gray-100">
            <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا العميل؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-3 rounded-xl border border-red-200 text-red-600 font-semibold hover:bg-red-50 transition-all duration-200">
                    <i class="fas fa-trash-alt ml-1"></i> حذف العميل
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
{{-- resources/views/cases/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'تعديل القضية: ' . $case->case_number)

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    {{-- Header & Breadcrumb --}}
    <div>
        <nav class="flex mb-2 text-sm text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1c5bb8] transition">لوحة التحكم</a>
            <span class="mx-2">/</span>
            <a href="{{ route('cases.index') }}" class="hover:text-[#1c5bb8] transition">القضايا</a>
            <span class="mx-2">/</span>
            <a href="{{ route('cases.show', $case) }}" class="hover:text-[#1c5bb8] transition">{{ $case->case_number }}</a>
            <span class="mx-2">/</span>
            <span class="text-gray-700">تعديل</span>
        </nav>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">تعديل القضية</h1>
        <p class="text-sm text-gray-500 mt-1">قم بتحديث بيانات القضية</p>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4">
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

    <form method="POST" action="{{ route('cases.update', $case) }}" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Form Area --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Section 1: Basic Info --}}
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-info-circle text-[#1c5bb8]"></i>
                        المعلومات الأساسية
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">رقم القضية <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <i class="fas fa-hashtag absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" name="case_number"
                                    value="{{ old('case_number', $case->case_number) }}" required
                                    class="w-full pr-10 pl-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200 @error('case_number') border-red-500 @enderror">
                            </div>
                            @error('case_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">عنوان القضية <span class="text-red-500">*</span></label>
                            <input type="text" name="title"
                                value="{{ old('title', $case->title) }}" required
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200 @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">الحالة</label>
                            <select name="status"
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200">
                                <option value="active"  {{ old('status', $case->status) == 'active'  ? 'selected' : '' }}>نشطة</option>
                                <option value="pending" {{ old('status', $case->status) == 'pending' ? 'selected' : '' }}>مجدولة</option>
                                <option value="closed"  {{ old('status', $case->status) == 'closed'  ? 'selected' : '' }}>مغلقة</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">درجة القضية</label>
                            <select name="degree"
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200">
                                <option value="first"     {{ old('degree', $case->degree) == 'first'     ? 'selected' : '' }}>ابتدائي</option>
                                <option value="appeal"    {{ old('degree', $case->degree) == 'appeal'    ? 'selected' : '' }}>استئناف</option>
                                <option value="cassation" {{ old('degree', $case->degree) == 'cassation' ? 'selected' : '' }}>نقض</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">الأولوية</label>
                            <select name="priority"
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200">
                                <option value="low"    {{ old('priority', $case->priority) == 'low'    ? 'selected' : '' }}>منخفضة</option>
                                <option value="medium" {{ old('priority', $case->priority) == 'medium' ? 'selected' : '' }}>متوسطة</option>
                                <option value="high"   {{ old('priority', $case->priority) == 'high'   ? 'selected' : '' }}>عالية</option>
                            </select>
                        </div>
                        @if(auth()->user()->isOwner())
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">إسناد القضية لمحامين</label>
                                <select name="lawyer_ids[]" multiple
                                    class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200 min-h-36">
                                    @php($selectedLawyerIds = old('lawyer_ids', $case->lawyers->pluck('id')->toArray()))
                                    @foreach($lawyersForAssignment as $lawyer)
                                        <option value="{{ $lawyer->id }}" {{ in_array($lawyer->id, $selectedLawyerIds) ? 'selected' : '' }}>
                                            {{ $lawyer->name }}
                                        </option>
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
                        @endif
                        {{-- FIX: start_date and next_session_date are now included in the edit form --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">تاريخ بدء القضية</label>
                            <input type="date" name="start_date"
                                value="{{ old('start_date', $case->start_date?->format('Y-m-d')) }}"
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">تاريخ الجلسة القادمة</label>
                            <input type="date" name="next_session_date"
                                value="{{ old('next_session_date', $case->next_session_date?->format('Y-m-d')) }}"
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200">
                        </div>
                    </div>
                </div>

                {{-- Section 2: Court Details --}}
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-gavel text-[#1c5bb8]"></i>
                        تفاصيل المحكمة
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">اسم المحكمة <span class="text-red-500">*</span></label>
                            <input type="text" name="court"
                                value="{{ old('court', $case->court) }}" required
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200 @error('court') border-red-500 @enderror">
                            @error('court')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">نوع القضية</label>
                            <input type="text" name="case_type"
                                value="{{ old('case_type', $case->case_type) }}"
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200">
                        </div>
                    </div>
                </div>

                {{-- Section 3: Financial Info --}}
                <div x-data="{
                    total: {{ old('fees_total', $case->fees_total) }},
                    paid:  {{ old('fees_paid',  $case->fees_paid) }},
                    get remaining() { return (this.total || 0) - (this.paid || 0); }
                }" class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-money-bill-wave text-[#1c5bb8]"></i>
                        المعلومات المالية
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">إجمالي الأتعاب (د.ج)</label>
                            <input type="number" step="0.01" min="0" name="fees_total"
                                x-model.number="total"
                                value="{{ old('fees_total', $case->fees_total) }}"
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">المدفوع (د.ج)</label>
                            <input type="number" step="0.01" min="0" name="fees_paid"
                                x-model.number="paid"
                                value="{{ old('fees_paid', $case->fees_paid) }}"
                                class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200">
                        </div>
                    </div>
                    <div class="mt-4 p-3 bg-gray-50 rounded-xl text-sm text-gray-600">
                        <i class="fas fa-calculator ml-1"></i>
                        المتبقي: <strong x-text="remaining.toFixed(2) + ' د.ج'"></strong>
                    </div>
                </div>

                {{-- Section 4: Clients --}}
                {{-- FIX: $clients is now passed by the controller — this section no longer crashes --}}
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-user-tie text-[#1c5bb8]"></i>
                        العملاء المرتبطين
                    </h2>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">اختر العملاء</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto border border-gray-200 rounded-2xl p-3 bg-gray-50">
                            @foreach($clients as $client)
                                <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-100 p-1 rounded">
                                    <input type="checkbox" name="client_ids[]" value="{{ $client->id }}"
                                        class="rounded border-gray-300 text-[#1c5bb8] focus:ring-[#1c5bb8]"
                                        {{ in_array($client->id, old('client_ids', $case->clients->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700">{{ $client->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-2">يمكنك اختيار أكثر من عميل.</p>
                    </div>
                    @error('client_ids')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Section 5: Notes --}}
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-paperclip text-[#1c5bb8]"></i>
                        ملاحظات
                    </h2>
                    <div>
                        <textarea name="description" rows="4"
                            class="w-full px-3 py-3.5 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition-all duration-200"
                            placeholder="أضف ملاحظات حول القضية...">{{ old('description', $case->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Sidebar Actions --}}
            <div class="space-y-6">
                <div class="bg-gray-50/80 rounded-2xl shadow-md border border-gray-100 p-6 sticky top-24">
                    <h3 class="font-bold text-lg text-gray-800 mb-5">إجراءات</h3>
                    <div class="space-y-4">
                        <button type="submit"
                            class="w-full py-3.5 rounded-xl bg-gradient-to-r from-[#1c5bb8] to-[#2a6dc9] text-white font-semibold hover:from-[#0f2d62] hover:to-[#1c5bb8] transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i> تحديث القضية
                        </button>
                        <a href="{{ route('cases.show', $case) }}"
                            class="w-full block text-center py-3.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all duration-200">
                            <i class="fas fa-eye ml-1"></i> عرض القضية
                        </a>
                        <a href="{{ route('cases.index') }}"
                            class="w-full block text-center py-3.5 rounded-xl text-gray-500 hover:text-gray-700 transition-all duration-200">
                            إلغاء
                        </a>
                    </div>
                    <div class="mt-5 pt-4 border-t border-gray-200 text-xs text-gray-400 text-center">
                        الحقول ذات <span class="text-red-500">*</span> إلزامية
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('title', 'تعديل المهمة')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">تعديل المهمة</h1>
        <p class="text-sm text-gray-500 mt-1">قم بتحديث بيانات المهمة</p>
    </div>

    <form method="POST" action="{{ route('tasks.update', $task) }}">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-lg font-bold text-gray-800">بيانات المهمة</h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">عنوان المهمة <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $task->title) }}" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('title') border-red-500 @enderror">
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">الوصف</label>
                            <textarea name="description" rows="4" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('description') border-red-500 @enderror">{{ old('description', $task->description) }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">قضية مرتبطة</label>
                            <select name="case_id" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('case_id') border-red-500 @enderror">
                                <option value="">-- بدون قضية --</option>
                                @foreach($cases as $case)
                                    <option value="{{ $case->id }}" {{ (string) old('case_id', $task->case_id) === (string) $case->id ? 'selected' : '' }}>{{ $case->case_number }} - {{ $case->title }}</option>
                                @endforeach
                            </select>
                            @error('case_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">إسناد إلى محامين</label>
                            @php($selectedLawyerIds = old('lawyer_ids', $task->lawyers->pluck('id')->toArray()))
                            <select name="lawyer_ids[]" multiple class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 min-h-36 @error('lawyer_ids') border-red-500 @enderror @error('lawyer_ids.*') border-red-500 @enderror">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ in_array($user->id, $selectedLawyerIds) ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">يمكنك اختيار أكثر من محامٍ.</p>
                            @error('lawyer_ids') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            @error('lawyer_ids.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">تاريخ الاستحقاق</label>
                            <input type="date" name="due_date" value="{{ old('due_date', optional($task->due_date)->format('Y-m-d')) }}" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('due_date') border-red-500 @enderror">
                            @error('due_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">وقت الاستحقاق</label>
                            <input type="time" name="due_time" value="{{ old('due_time', optional($task->due_time)->format('H:i')) }}" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('due_time') border-red-500 @enderror">
                            @error('due_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">الأولوية</label>
                            <select name="priority" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('priority') border-red-500 @enderror">
                                <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>منخفضة</option>
                                <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>متوسطة</option>
                                <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>عالية</option>
                            </select>
                            @error('priority') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">الحالة</label>
                            <select name="status" class="w-full px-3 py-3 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 @error('status') border-red-500 @enderror">
                                <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>معلقة</option>
                                <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                                <option value="done" {{ old('status', $task->status) === 'done' ? 'selected' : '' }}>منجزة</option>
                            </select>
                            @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="bg-gray-50/80 rounded-2xl shadow-md border border-gray-100 p-6 sticky top-24">
                    <h3 class="font-bold text-lg text-gray-800 mb-5">إجراءات</h3>
                    <div class="space-y-4">
                        <button type="submit" class="w-full py-3.5 rounded-xl bg-[#1c5bb8] text-white font-semibold hover:bg-[#0f2d62] transition shadow-sm flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i> تحديث المهمة
                        </button>
                        <a href="{{ route('tasks.show', $task) }}" class="w-full block text-center py-3.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition">عرض المهمة</a>
                        <a href="{{ route('tasks.index') }}" class="w-full block text-center py-3.5 rounded-xl text-gray-500 hover:text-gray-700 transition">إلغاء</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

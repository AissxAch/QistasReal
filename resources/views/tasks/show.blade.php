@extends('layouts.app')

@section('title', 'تفاصيل المهمة')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="flex flex-wrap justify-between items-start gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">تفاصيل المهمة</h1>
            <p class="text-sm text-gray-500 mt-1">عرض كامل لبيانات المهمة</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1c5bb8] text-white hover:bg-[#0f2d62] transition shadow-sm">
                <i class="fas fa-edit"></i>
                <span>تعديل</span>
            </a>
            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه المهمة؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-red-200 text-red-600 hover:bg-red-50 transition">
                    <i class="fas fa-trash-alt"></i>
                    <span>حذف</span>
                </button>
            </form>
            <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                <i class="fas fa-arrow-right"></i>
                <span>عودة</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between gap-3">
            <h2 class="text-lg font-bold text-gray-800">{{ $task->title }}</h2>
            <div class="flex gap-2">
                @if($task->priority === 'high')
                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">عالية</span>
                @elseif($task->priority === 'medium')
                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">متوسطة</span>
                @else
                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">منخفضة</span>
                @endif

                @if($task->status === 'done')
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">منجزة</span>
                @elseif($task->status === 'in_progress')
                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">قيد التنفيذ</span>
                @else
                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">معلقة</span>
                @endif
            </div>
        </div>

        <div class="p-6 space-y-8">
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">بيانات المهمة</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 mb-1">تاريخ الاستحقاق</h4>
                        <p class="text-gray-900">{{ $task->due_date ? $task->due_date->format('Y-m-d') : 'غير محدد' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 mb-1">وقت الاستحقاق</h4>
                        <p class="text-gray-900">{{ $task->due_time ? optional($task->due_time)->format('H:i') : 'غير محدد' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-semibold text-gray-500 mb-1">الوصف</h4>
                        <p class="text-gray-900 whitespace-pre-line">{{ $task->description ?: 'لا يوجد وصف' }}</p>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100">
                <div class="px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 mb-4">
                    <h3 class="text-lg font-bold text-gray-800">القضية المرتبطة</h3>
                </div>
                @if($task->case)
                    <div class="space-y-2">
                        <p class="text-gray-900"><span class="text-gray-500">رقم القضية:</span> {{ $task->case->case_number }}</p>
                        <p class="text-gray-900"><span class="text-gray-500">العنوان:</span> {{ $task->case->title }}</p>
                        <a href="{{ route('cases.show', $task->case) }}" class="inline-flex items-center gap-2 text-sm text-[#1c5bb8] hover:underline">
                            عرض تفاصيل القضية
                            <i class="fas fa-arrow-left text-xs"></i>
                        </a>
                    </div>
                @else
                    <p class="text-sm text-gray-500">لا توجد قضية مرتبطة.</p>
                @endif

                @if($task->case && $task->case->clients->isNotEmpty())
                    <div class="mt-4">
                        <h4 class="text-sm font-semibold text-gray-500 mb-2">العملاء المرتبطون بالقضية</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($task->case->clients as $client)
                                <a href="{{ route('clients.show', $client) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-800 rounded-full text-sm hover:bg-blue-100 transition">
                                    <i class="fas fa-user-circle text-xs"></i>
                                    <span>{{ $client->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="pt-6 border-t border-gray-100">
                <div class="px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 mb-4">
                    <h3 class="text-lg font-bold text-gray-800">المحامون المسند إليهم</h3>
                </div>
                @if($task->lawyers->isNotEmpty())
                    <div class="flex flex-wrap gap-2">
                        @foreach($task->lawyers as $lawyer)
                            @if(auth()->user()?->isOwner())
                                <a href="{{ route('team.edit', $lawyer) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-800 rounded-full text-sm hover:bg-blue-100 transition">
                                    <i class="fas fa-user-circle text-xs"></i>
                                    <span>{{ $lawyer->name }}</span>
                                </a>
                            @else
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-800 rounded-full text-sm">
                                    <i class="fas fa-user-circle text-xs"></i>
                                    <span>{{ $lawyer->name }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @elseif($task->assignedTo)
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-800 rounded-full text-sm">
                        <i class="fas fa-user-circle text-xs"></i>
                        <span>{{ $task->assignedTo->name }}</span>
                    </div>
                @else
                    <p class="text-sm text-gray-500">المهمة غير مسندة لأي مستخدم.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

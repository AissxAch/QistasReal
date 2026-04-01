{{-- resources/views/cases/show.blade.php --}}
@extends('layouts.app')

@section('title', 'عرض القضية: ' . $case->case_number)

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    {{-- Header & Breadcrumb --}}
    <div>
        <nav class="flex mb-2 text-sm text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1c5bb8] transition">لوحة التحكم</a>
            <span class="mx-2">/</span>
            <a href="{{ route('cases.index') }}" class="hover:text-[#1c5bb8] transition">القضايا</a>
            <span class="mx-2">/</span>
            <span class="text-gray-700">{{ $case->case_number }}</span>
        </nav>
        <div class="flex flex-wrap justify-between items-start gap-4">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $case->title }}</h1>
            <div class="flex gap-2">
                <a href="{{ route('cases.edit', $case) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1c5bb8] text-white hover:bg-[#0f2d62] transition shadow-sm">
                    <i class="fas fa-edit"></i>
                    <span>تعديل</span>
                </a>
                <form action="{{ route('cases.destroy', $case) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه القضية؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-red-200 text-red-600 hover:bg-red-50 transition">
                        <i class="fas fa-trash-alt"></i>
                        <span>حذف</span>
                    </button>
                </form>
                <a href="{{ route('cases.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-right"></i>
                    <span>عودة</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        {{-- Header with status & priority badges --}}
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/30 flex flex-wrap justify-between items-center gap-3">
            <div class="flex gap-2">
                @if($case->status == 'active')
                    <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800 font-medium">نشطة</span>
                @elseif($case->status == 'pending')
                    <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800 font-medium">مجدولة</span>
                @else
                    <span class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600 font-medium">مغلقة</span>
                @endif

                @if($case->priority == 'low')
                    <span class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600">أولوية منخفضة</span>
                @elseif($case->priority == 'medium')
                    <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800">أولوية متوسطة</span>
                @else
                    <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800">أولوية عالية</span>
                @endif
            </div>
            <div class="text-sm text-gray-500">
                <i class="fas fa-hashtag ml-1"></i>
                <span>رقم القضية: {{ $case->case_number }}</span>
            </div>
        </div>

        {{-- Info Grid --}}
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 mb-1">المحكمة</h3>
                    <p class="text-gray-900">{{ $case->court }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 mb-1">نوع القضية</h3>
                    <p class="text-gray-900">{{ $case->case_type ?? 'غير محدد' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 mb-1">درجة القضية</h3>
                    <p class="text-gray-900">
                        @if($case->degree == 'first') ابتدائي
                        @elseif($case->degree == 'appeal') استئناف
                        @else نقض
                        @endif
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 mb-1">الحالة</h3>
                    <p class="text-gray-900">
                        @if($case->status == 'active') نشطة
                        @elseif($case->status == 'pending') مجدولة
                        @else مغلقة
                        @endif
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 mb-1">الأولوية</h3>
                    <p class="text-gray-900">
                        @if($case->priority == 'low') منخفضة
                        @elseif($case->priority == 'medium') متوسطة
                        @else عالية
                        @endif
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 mb-1">تاريخ بدء القضية</h3>
                    <p class="text-gray-900">{{ $case->start_date ? \Carbon\Carbon::parse($case->start_date)->format('Y-m-d') : 'غير محدد' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 mb-1">تاريخ الجلسة القادمة</h3>
                    <p class="text-gray-900">{{ $case->next_session_date ? \Carbon\Carbon::parse($case->next_session_date)->format('Y-m-d') : 'غير محدد' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 mb-1">المحامون المكلّفون</h3>
                    @if($case->lawyers->isNotEmpty())
                        <div class="flex flex-wrap gap-2">
                            @foreach($case->lawyers as $lawyer)
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-800 rounded-full text-sm">
                                    <i class="fas fa-user-circle text-xs"></i>
                                    <span>{{ $lawyer->name }}</span>
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-900">{{ $case->assignedLawyer?->name ?? 'غير مُسنَد' }}</p>
                    @endif
                </div>
            </div>

            {{-- Financial Section --}}
            <div class="mt-8 pt-6 border-t border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-money-bill-wave text-[#1c5bb8]"></i>
                    المعلومات المالية
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-sm text-gray-500">إجمالي الأتعاب</div>
                        <div class="text-xl font-bold text-gray-900">{{ number_format($case->fees_total, 2) }} د.ج</div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-sm text-gray-500">المدفوع</div>
                        <div class="text-xl font-bold text-green-600">{{ number_format($case->fees_paid, 2) }} د.ج</div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-sm text-gray-500">المتبقي</div>
                        <div class="text-xl font-bold text-red-600">{{ number_format($case->fees_remaining, 2) }} د.ج</div>
                    </div>
                </div>
            </div>

            {{-- Clients Section --}}
            <div class="mt-8 pt-6 border-t border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-user-tie text-[#1c5bb8]"></i>
                    العملاء المرتبطين
                </h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($case->clients as $client)
                        <a href="{{ route('clients.show', $client) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-800 rounded-full text-sm hover:bg-blue-100 transition">
                            <i class="fas fa-user-circle text-xs"></i>
                            <span>{{ $client->name }}</span>
                            @if($client->phone)
                                <span class="text-xs text-gray-500">({{ $client->phone }})</span>
                            @endif
                        </a>
                    @empty
                        <p class="text-gray-500 text-sm">لا يوجد عملاء مرتبطون بهذه القضية.</p>
                    @endforelse
                </div>
            </div>

            {{-- Description Section --}}
            @if($case->description)
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-paperclip text-[#1c5bb8]"></i>
                        ملاحظات
                    </h3>
                    <div class="bg-gray-50 rounded-xl p-4 text-gray-700 whitespace-pre-line">
                        {{ $case->description }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
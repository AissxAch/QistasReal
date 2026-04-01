@extends('layouts.app')

@section('title', 'تفاصيل الجلسة')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div>
        <nav class="flex mb-2 text-sm text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1c5bb8] transition">لوحة التحكم</a>
            <span class="mx-2">/</span>
            <a href="{{ route('sessions.index') }}" class="hover:text-[#1c5bb8] transition">الجلسات</a>
            <span class="mx-2">/</span>
            <span class="text-gray-700">تفاصيل الجلسة</span>
        </nav>

        <div class="flex flex-wrap justify-between items-start gap-4">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">تفاصيل الجلسة</h1>
            <div class="flex gap-2">
                <a href="{{ route('sessions.edit', $session) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1c5bb8] text-white hover:bg-[#0f2d62] transition shadow-sm">
                    <i class="fas fa-edit"></i>
                    <span>تعديل</span>
                </a>
                <form action="{{ route('sessions.destroy', $session) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الجلسة؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-red-200 text-red-600 hover:bg-red-50 transition">
                        <i class="fas fa-trash-alt"></i>
                        <span>حذف</span>
                    </button>
                </form>
                <a href="{{ route('sessions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-right"></i>
                    <span>عودة</span>
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex flex-wrap justify-between items-center gap-3">
            <div class="flex gap-2">
                @if($session->status === 'scheduled')
                    <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800 font-medium">مجدولة</span>
                @elseif($session->status === 'done')
                    <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800 font-medium">منعقدت</span>
                @elseif($session->status === 'postponed')
                    <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800 font-medium">مؤجلة</span>
                @else
                    <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800 font-medium">ملغاة</span>
                @endif
            </div>
            <div class="text-sm text-gray-500">
                <i class="fas fa-calendar-days ml-1"></i>
                <span>{{ optional($session->session_date)->format('Y-m-d') }} - {{ optional($session->session_time)->format('H:i') ?? '—' }}</span>
            </div>
        </div>

        <div class="p-6 space-y-8">
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">بيانات الجلسة</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 mb-1">التاريخ</h4>
                        <p class="text-gray-900">{{ optional($session->session_date)->format('Y-m-d') }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 mb-1">الوقت</h4>
                        <p class="text-gray-900">{{ optional($session->session_time)->format('H:i') ?? '—' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 mb-1">المحكمة</h4>
                        <p class="text-gray-900">{{ $session->court }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 mb-1">القاعة</h4>
                        <p class="text-gray-900">{{ $session->room ?? 'غير محددة' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-semibold text-gray-500 mb-1">ملاحظات</h4>
                        <p class="text-gray-900 whitespace-pre-line">{{ $session->notes ?: 'لا توجد ملاحظات' }}</p>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100">
                <div class="px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 mb-4">
                    <h3 class="text-lg font-bold text-gray-800">القضية المرتبطة</h3>
                </div>

                @if($session->case)
                    <div class="space-y-2">
                        <p class="text-gray-900"><span class="text-gray-500">رقم القضية:</span> {{ $session->case->case_number }}</p>
                        <p class="text-gray-900"><span class="text-gray-500">العنوان:</span> {{ $session->case->title }}</p>
                        <a href="{{ route('cases.show', $session->case) }}" class="inline-flex items-center gap-2 text-sm text-[#1c5bb8] hover:underline">
                            عرض تفاصيل القضية
                            <i class="fas fa-arrow-left text-xs"></i>
                        </a>
                    </div>
                @else
                    <p class="text-sm text-gray-500">لا توجد قضية مرتبطة.</p>
                @endif
            </div>

            <div class="pt-6 border-t border-gray-100">
                <div class="px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 mb-4">
                    <h3 class="text-lg font-bold text-gray-800">العملاء المرتبطون</h3>
                </div>

                @if($session->case && $session->case->clients->isNotEmpty())
                    <div class="flex flex-wrap gap-2">
                        @foreach($session->case->clients as $client)
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-800 rounded-full text-sm">
                                <i class="fas fa-user-circle text-xs"></i>
                                <span>{{ $client->name }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">لا يوجد عملاء مرتبطون بهذه القضية.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

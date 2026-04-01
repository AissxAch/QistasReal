@extends('layouts.app')

@section('title', $client->name)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header with quick actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-gray-100">
        <div>
            <nav class="flex mb-2 text-sm text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-[#1c5bb8] transition">لوحة التحكم</a>
                <span class="mx-2">/</span>
                <a href="{{ route('clients.index') }}" class="hover:text-[#1c5bb8] transition">العملاء</a>
                <span class="mx-2">/</span>
                <span class="text-gray-700">{{ $client->name }}</span>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $client->name }}</h1>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1c5bb8] text-white hover:bg-[#0f2d62] transition shadow-sm">
                <i class="fas fa-edit"></i> تعديل
            </a>
            <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا العميل؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition">
                    <i class="fas fa-trash-alt"></i> حذف
                </button>
            </form>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-[#1c5bb8] to-[#c9a227] flex items-center justify-center text-white text-3xl font-bold shadow-md">
                    {{ strtoupper(substr($client->name, 0, 1)) }}
                </div>
            </div>
            <!-- Details -->
            <div class="flex-1 space-y-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">الهاتف</p>
                        <p class="text-gray-800 font-medium" dir="ltr">{{ $client->phone ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">البريد الإلكتروني</p>
                        <p class="text-gray-800 font-medium" dir="ltr">{{ $client->email ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">العنوان</p>
                        <p class="text-gray-800 font-medium">{{ $client->address ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">رقم الهوية</p>
                        <p class="text-gray-800 font-medium" dir="ltr">{{ $client->id_number ?? '—' }}</p>
                    </div>
                </div>
                @if($client->notes)
                    <div>
                        <p class="text-sm text-gray-500">ملاحظات</p>
                        <p class="text-gray-800 whitespace-pre-line">{{ $client->notes }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500">المحامون المكلّفون</p>
                    @if($client->lawyers->isNotEmpty())
                        <div class="mt-1 flex flex-wrap gap-2">
                            @foreach($client->lawyers as $lawyer)
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-800 rounded-full text-sm">
                                    <i class="fas fa-user-circle text-xs"></i>
                                    <span>{{ $lawyer->name }}</span>
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-800 font-medium">غير مُسنَد</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Cases Section -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-100 pb-3">
            <i class="fas fa-gavel text-[#1c5bb8]"></i>
            القضايا المرتبطة
            <span class="text-sm font-normal text-gray-500 mr-2">({{ $client->cases->count() }})</span>
        </h2>

        @if($client->cases->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">رقم القضية</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">العنوان</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">المحكمة</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">الدور</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider"></th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @foreach($client->cases as $case)
                        <tr class="hover:bg-gray-50/80 transition-all duration-200">
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $case->case_number }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $case->title }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $case->court }}</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($case->status === 'active')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> نشطة
                                    </span>
                                @elseif($case->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                        <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span> مجدولة
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> مغلقة
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                @php
                                    $roleMap = ['plaintiff' => 'مدعي', 'defendant' => 'مدعى عليه', 'witness' => 'شاهد', 'other' => 'آخر'];
                                @endphp
                                {{ $roleMap[$case->pivot->role] ?? $case->pivot->role }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('cases.show', $case) }}" class="text-[#1c5bb8] hover:underline">عرض</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-gavel text-5xl text-gray-200 mb-3 block"></i>
                <p class="text-gray-500">لا توجد قضايا مرتبطة بهذا العميل</p>
                <a href="{{ route('cases.create', ['client_id' => $client->id]) }}" class="mt-3 inline-block text-sm text-[#1c5bb8] hover:underline font-medium">
                    إضافة قضية جديدة
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
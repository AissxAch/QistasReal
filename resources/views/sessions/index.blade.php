@extends('layouts.app')

@section('title', 'الجلسات')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">الجلسات</h1>
            <p class="text-sm text-gray-500 mt-1">إدارة جلسات المحكمة ومواعيدها</p>
        </div>
        <a href="{{ route('sessions.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1c5bb8] text-white hover:bg-[#0f2d62] transition shadow-sm">
            <i class="fas fa-plus-circle"></i>
            <span>إضافة جلسة</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <form method="GET" action="{{ route('sessions.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ $search ?? request('search') }}" placeholder="ابحث باسم المحكمة أو عنوان القضية..."
                        class="w-full pr-10 pl-3 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-[#1c5bb8] focus:ring-2 focus:ring-[#1c5bb8]/20 transition">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-[#1c5bb8] text-white hover:bg-[#0f2d62] transition">بحث</button>
                    <a href="{{ route('sessions.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition">إعادة تعيين</a>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوقت</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">القضية</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المحكمة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">القاعة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($sessions as $session)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ optional($session->session_date)->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ optional($session->session_time)->format('H:i') ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $session->case->title ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $session->court }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $session->room ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($session->status === 'scheduled')
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 font-medium">مجدولة</span>
                                @elseif($session->status === 'done')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 font-medium">منعقدت</span>
                                @elseif($session->status === 'postponed')
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 font-medium">مؤجلة</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 font-medium">ملغاة</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('sessions.show', $session) }}" class="text-gray-400 hover:text-[#1c5bb8] transition" title="عرض"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('sessions.edit', $session) }}" class="text-gray-400 hover:text-[#1c5bb8] transition" title="تعديل"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('sessions.destroy', $session) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الجلسة؟')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition" title="حذف">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-calendar-xmark text-5xl text-gray-300 mb-3 block"></i>
                                <p>لا توجد جلسات حالياً</p>
                                <a href="{{ route('sessions.create') }}" class="mt-2 inline-block text-sm text-[#1c5bb8] hover:underline">إضافة جلسة جديدة</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        {{ $sessions->links() }}
    </div>
</div>
@endsection
